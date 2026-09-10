<?php

namespace Templates\Clinic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Templates\Clinic\Models\Appointment;

/**
 * Admin: randevu durumu ve iç not güncelleme.
 */
class AppointmentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-sector') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(array_keys(Appointment::STATUSES))],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $data = $this->validated();
        $data['notes'] = ($data['notes'] ?? null) ?: null;

        return $data;
    }
}
