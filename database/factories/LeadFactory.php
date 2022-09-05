<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      "first_name" => $this->faker->firstName,
      "last_name" => $this->faker->lastName,
      "full_name" => $this->faker->name,
      "email" => $this->faker->unique()->safeEmail,
      "phone" => $this->faker->phoneNumber,
      "alternate_phone" => $this->faker->phoneNumber,
      "address" => $this->faker->address,
      "city" => $this->faker->city,
      "requirement" => $this->faker->sentence($nbWords = 2, $variableNbWords = true),
    ];
  }
}