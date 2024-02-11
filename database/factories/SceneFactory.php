<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Application;
use App\Models\Organization;
use App\Models\Scene;

class SceneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Scene::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sort_by' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'application_id' => Application::factory(),
            'organization_id' => Organization::factory(),
        ];
    }
}
