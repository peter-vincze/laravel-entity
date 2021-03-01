<?php

namespace Database\Factories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Entity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'salary'      => mt_rand(600000,900000),
            'name'        => $this->faker->name,
            'email'       => $this->faker->unique()->safeEmail,
            'docker'      => $this->faker->boolean(),
            'agile'       => $this->faker->boolean(),
            'start'       => date('Y-m-d', $this->faker->dateTimeBetween(' + 1 week', ' + 3 weeks')->getTimestamp()),
            'senior'      => mt_rand(0,1) ? $this->faker->boolean() : null,
            'fullstack'   => mt_rand(0,1) ? $this->faker->boolean() : null,
            'description' => mt_rand(0,1) ? $this->faker->paragraph() : null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
