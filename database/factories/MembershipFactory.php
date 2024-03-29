<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Membership;
use App\Models\Organization;
use App\Models\User;

class MembershipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Membership::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'is_org_admin' => $this->faker->boolean(),
            'is_org_manager' => $this->faker->boolean(),
            'user_id' => null,
            'organization_id' => null,
        ];
    }
}
