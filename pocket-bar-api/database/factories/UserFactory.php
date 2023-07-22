<?php

namespace Database\Factories;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'rol_id' => $this->faker->unique(true)->numberBetween(1, count(Rol::toArray())),
            'nominas' => $this->faker->numerify('###.##'),
            'password' => '12345678', // password
            'remember_token' => Str::random(10),
            'branch_id' => 1,
        ];
    }
}
