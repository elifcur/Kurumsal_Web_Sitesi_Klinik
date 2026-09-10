<?php

namespace Templates\Clinic\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Templates\Clinic\Http\Requests\AppointmentStatusRequest;
use Templates\Clinic\Models\Appointment;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $appointments = Appointment::query()
            ->with(['service', 'doctor'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = '%'.$request->string('q')->limit(100).'%';
                $query->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('phone', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->when(array_key_exists((string) $request->input('status'), Appointment::STATUSES), fn ($q) => $q->status($request->input('status')))
            ->when($request->filled('doctor'), fn ($q) => $q->where('team_member_id', $request->integer('doctor')))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('template::admin.appointments.index', [
            'appointments' => $appointments,
            'doctors' => TeamMember::query()->ordered()->pluck('name', 'id'),
            'counts' => Appointment::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['service', 'doctor']);

        return view('template::admin.appointments.show', ['appointment' => $appointment]);
    }

    public function update(AppointmentStatusRequest $request, Appointment $appointment): RedirectResponse
    {
        $appointment->update($request->payload());

        return redirect()->route('admin.appointments.show', $appointment)->with('success', 'Randevu güncellendi.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('success', 'Randevu silindi.');
    }
}
