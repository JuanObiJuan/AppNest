<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Application;
use App\Models\Organization;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'default_language' => $this->faker->word(),
            'languages' => '{}',
            'json_data' => '{}',
            'json_schema' => '{}',
            'json_admin_ui_schema' => '{}',
            'json_manager_ui_schema' => '{}',
            'organization_id' => null,
        ];
    }
}
