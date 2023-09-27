<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(100),
            'price' => $this->faker->numberBetween(1, 100),
            'category_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'type_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'provider_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'status_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'brand_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'user_id' => $this->faker->unique(true)->numberBetween(1, 10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
