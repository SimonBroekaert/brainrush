<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->paragraph(),
            'is_public' => fake()->boolean(20),
        ];
    }

    /**
     * Indicate that the Group is public.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
     */
    public function public(): Factory
    {
        return $this->state([
            'is_public' => true,
        ]);
    }

    /**
     * Indicate that the Group is private.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
     */
    public function private(): Factory
    {
        return $this->state([
            'is_public' => false,
        ]);
    }
}
