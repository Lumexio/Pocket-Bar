<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Articulo;

class ArticuloFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Articulo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre_articulo' => $this->faker->name(),
            'cantidad_articulo' => $this->faker->numberBetween(1, 100),
            'precio_articulo' => $this->faker->numberBetween(1, 100),
            'categoria_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'status_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'rack_id' => $this->faker->unique(true)->numberBetween(1, 2),
            'tipo_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'travesano_id' => $this->faker->unique(true)->numberBetween(1, 2),
            'proveedor_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'marca_id' => $this->faker->unique(true)->numberBetween(1, 3),
            'user_id' => $this->faker->unique(true)->numberBetween(1, 10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
