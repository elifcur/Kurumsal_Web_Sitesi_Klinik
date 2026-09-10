<?php

namespace Templates\Clinic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Templates\Clinic\Models\Appointment;

/**
 * Public randevu talebi formu (hero mini formu ve /randevu sayfası aynı isteği kullanır).
 */
class AppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:40'],
            'service_id' => ['nullable', 'integer', Rule::exists('services', 'id')->where('is_active', true)],
            'team_member_id' => ['nullable', 'integer', Rule::exists('team_members', 'id')->where('is_active', true)],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', Rule::in(array_keys(Appointment::TIMES))],
            'message' => ['nullable', 'string', 'max:2000'],
            'kvkk' => ['accepted'],
            'website' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'ad soyad',
            'email' => 'e-posta',
            'phone' => 'telefon',
            'service_id' => 'bölüm',
            'team_member_id' => 'doktor',
            'preferred_date' => 'tarih',
            'preferred_time' => 'saat aralığı',
            'message' => 'mesaj',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kvkk.accepted' => 'KVKK metnini onaylamalısınız.',
            'preferred_date.after_or_equal' => 'Geçmiş bir tarih için randevu talep edilemez.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $data = $this->safe()->only(['name', 'email', 'phone', 'service_id', 'team_member_id', 'preferred_date', 'preferred_time', 'message']);
        $data['email'] = ($data['email'] ?? null) ?: null;
        $data['service_id'] = ($data['service_id'] ?? null) ?: null;
        $data['team_member_id'] = ($data['team_member_id'] ?? null) ?: null;
        $data['preferred_time'] = ($data['preferred_time'] ?? null) ?: null;
        $data['message'] = ($data['message'] ?? null) ?: null;
        $data['status'] = 'pending';
        $data['ip'] = $this->ip();

        return $data;
    }
}
