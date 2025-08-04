<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class LecturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nidn' => $this->faker->numerify('1192######'),
            'nip' => $this->faker->numerify('11234############'),
            'lecturer_phone_number' => $this->faker->phoneNumber,
            'lecturer_address' => $this->faker->address,
            'user_id' => User::factory(),
        ];
    }
}
