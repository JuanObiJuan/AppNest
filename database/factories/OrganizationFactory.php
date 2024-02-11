<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Organization;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'cover_members_cost' => $this->faker->boolean(),
            'allow_guests' => $this->faker->boolean(),
            'cover_guests_cost' => $this->faker->boolean(),
            'website' => $this->faker->word(),
            'email' => $this->faker->safeEmail(),
        ];
    }
}
