<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SpeakerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'position' => $this->faker->jobTitle(),
            'organization' => $this->faker->company(),
            'description' => '<p>' . $this->faker->paragraph(3) . '</p>',
            'photo' => null,
        ];
    }
}
