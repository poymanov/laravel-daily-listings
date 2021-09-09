<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Listing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'       => $this->faker->word() . '_' . $this->faker->randomNumber(),
            'description' => $this->faker->text(),
            'price'       => rand(10, 999),
            'user_id'     => User::factory(),
        ];
    }
}
