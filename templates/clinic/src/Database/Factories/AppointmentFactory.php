<?php

namespace Templates\Clinic\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Templates\Clinic\Models\Appointment;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'preferred_date' => fake()->dateTimeBetween('+1 day', '+21 days')->format('Y-m-d'),
            'preferred_time' => fake()->randomElement(array_keys(Appointment::TIMES)),
            'message' => fake()->boolean(60) ? fake()->sentence(12) : null,
            'status' => 'pending',
            'ip' => fake()->ipv4(),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn () => ['status' => 'confirmed', 'notes' => 'Hasta arandı, randevu teyit edildi.']);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => 'cancelled', 'notes' => 'Hasta talebi üzerine iptal edildi.']);
    }
}
