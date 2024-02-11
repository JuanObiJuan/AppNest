<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Application;
use App\Models\AttributeCollection;
use App\Models\Scene;
use App\Models\Voice;

class AttributeCollectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttributeCollection::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'languages' => '{}',
            'json_schema' => '{}',
            'json_ui_schema' => '{}',
            'application_id' => Application::factory(),
            'scene_id' => Scene::factory(),
            'voice_id' => Voice::factory(),
        ];
    }
}
