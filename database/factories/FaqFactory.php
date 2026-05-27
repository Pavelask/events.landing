<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence(8),
            'answer' => '<p>' . $this->faker->paragraph(4) . '</p>',
        ];
    }
}
