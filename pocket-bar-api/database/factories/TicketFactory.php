<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $items = [];

        $totalItems = $this->faker->numberBetween(1, 5);
        $total = 0;
        $subtotal = 0;
        $tax = 0;

        for ($i = 0; $i < $totalItems; $i++) {
            $units = $this->faker->numberBetween(1, 5);
            $price = $this->faker->randomFloat(2, 1, 100);
            $subtotal += $units * $price;
            $tax += $units * $price * 0.16;
            $total += $units * $price + (($units * $price) * 0.16);
            $items[] = [
                "units" => $units,
                "unit_price" => $price,
                "discounts" => 0,
                "tax" => ($units * $price) * 0.16,
                "subtotal" => $units * $price,
                "total" => $units * $price + (($units * $price) * 0.16),
                "articulos_tbl_id" => $this->faker->numberBetween(1, 10),
                "articulos_img" => "https://via.placeholder.com/500x500",
                "attended" => $this->faker->boolean(50),
                "created_at" => now(),
            ];
        }
        $user = $this->faker->randomElement(['14', '15']);
        $table = $this->faker->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']);
        $userName = [
            '14' => 'mesero',
            '15' => 'bartender',
        ];

        $date = $this->faker->dateTimeBetween('-2 hours', 'now');
        return [
            'total' => $total,
            'subtotal' => $subtotal,
            "tax" => $tax,
            'item_count' => $totalItems,
            "user_name" => $userName[$user],
            'ticket_date' => $date,
            "user_id" => $user,
            "tip" => $this->faker->randomElement([.05, .1, .15, .2]) * $subtotal,
            "discounts" => 0,
            "min_tip" => $subtotal >= 500 ? $subtotal * 0.10 : $subtotal,
            "table_id" => $table,
            "table_name" => $table,
            "status" => "Pedido nuevo",
            "workshift_id" => 1,
            "closed" => 0,
            "created_at" => $date,
            'items' => $items,
        ];
    }
}
