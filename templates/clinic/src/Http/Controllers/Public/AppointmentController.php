<?php

namespace Templates\Clinic\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Public\ContactController;
use App\Models\ContactMessage;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Templates\Clinic\Http\Requests\AppointmentRequest;
use Templates\Clinic\Models\Appointment;

class AppointmentController extends Controller
{
    /**
     * Randevu formu. ?tedavi={slug} ve ?doktor={slug} ile ön seçim yapılır.
     */
    public function create(Request $request): View
    {
        $services = Service::query()->active()->ordered()->get(['id', 'title', 'slug']);
        $doctors = TeamMember::query()->active()->ordered()->get(['id', 'name', 'slug', 'title', 'image', 'attributes']);

        return view('template::appointments.create', [
            'services' => $services,
            'doctors' => $doctors,
            'selectedService' => $services->firstWhere('slug', (string) $request->query('tedavi')),
            'selectedDoctor' => $doctors->firstWhere('slug', (string) $request->query('doktor')),
        ]);
    }

    public function store(AppointmentRequest $request): RedirectResponse
    {
        $appointment = Appointment::query()->create($request->payload());
        $appointment->load(['service', 'doctor']);

        $message = ContactMessage::query()->create([
            'type' => 'appointment',
            'name' => $appointment->name,
            'email' => $appointment->email ?? '',
            'phone' => $appointment->phone,
            'subject' => 'Randevu Talebi: '.($appointment->service?->title ?? 'Genel'),
            'message' => $appointment->message ?: 'Randevu talep ediyorum.',
            'meta' => array_filter([
                'Randevu No' => '#'.$appointment->id,
                'Bölüm' => $appointment->service?->title,
                'Doktor' => $appointment->doctor?->name,
                'Tarih' => $appointment->preferred_date->format('d.m.Y'),
                'Saat' => $appointment->timeLabel(),
            ]),
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        ContactController::notify($message);

        return redirect()->route('appointments.create')->with('success', setting('appointment_success', 'Randevu talebiniz alındı. Hasta danışmanımız en kısa sürede sizinle iletişime geçecek.'));
    }
}
