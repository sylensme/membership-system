<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
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
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'membership_number' => 'MEM-'.fake()->unique()->regexify('[A-Z0-9]{10}'),
            'membership_category_id' => MembershipCategory::query()->firstOrCreate(['name' => 'Regular'])->id,
            'membership_level_id' => MembershipLevel::query()->firstOrCreate(['name' => 'Basic'], ['fee' => 500, 'validity_months' => 12])->id,
            'address' => fake()->address(),
            'is_active' => true,
        ];
    }
}
