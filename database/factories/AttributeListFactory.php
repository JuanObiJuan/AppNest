<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AttributeCollection;
use App\Models\AttributeList;

class AttributeListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttributeList::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'language_key' => $this->faker->word(),
            'json_schema' => '{}',
            'json_ui_schema' => '{}',
            'json_data' => '{}',
            'attribute_collection_id' => AttributeCollection::factory(),
        ];
    }
}
