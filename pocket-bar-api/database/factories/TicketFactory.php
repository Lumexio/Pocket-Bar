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

        $user = $this->faker->randomElement(['14', '15']);
        $table = $this->faker->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']);

        for ($i = 0; $i < $totalItems; $i++) {
            $units = $this->faker->numberBetween(1, 5);
            $price = $this->faker->randomFloat(2, 1, 100);
            $subtotal += $units * $price;
            $tax += $units * $price * 0.16;
            $total += $units * $price + (($units * $price) * 0.16);
            $status = $this->faker->randomElement(['En espera', 'En preparacion', "Preparado", 'Recibido']);
            $items[] = [
                "units" => $units,
                "discounts" => 0,
                "tax" => ($units * $price) * 0.16,
                "subtotal" => $units * $price,
                "total" => $units * $price + (($units * $price) * 0.16),
                "product_id" => $this->faker->numberBetween(1, 10),
                "status" => $status,
                "barTender_id" => $status == "En espera" ? null : $this->faker->randomElement([15, 16]),
                "waiter_id" => $this->faker->numberBetween(1, 10),
                "created_at" => now(),
            ];
        }


        $date = $this->faker->dateTimeBetween('-2 hours', 'now');
        $statusTicket = $this->faker->randomElement(['Entregado', 'Por entregar', 'Cerrado']);
        return [
            'total' => $total,
            'subtotal' => $subtotal,
            "tax" => $tax,
            "timezone" => "America/Denver",
            "client_name" => $this->faker->name("Male"),
            'item_count' => $totalItems,
            'ticket_date' => $date,
            "user_id" => $user,
            "tip" => $this->faker->randomElement([.05, .1, .15, .2]) * $subtotal,
            "discounts" => 0,
            "min_tip" => $subtotal >= 500 ? $subtotal * 0.10 : $subtotal,
            "table_id" => $table,
            "status" => $statusTicket,
            "branch_id" => 1,
            "workshift_id" => 1,
            "closed" => $statusTicket == "Cerrado" ? true : false,
            "created_at" => $date,
            'items' => $items,
        ];
    }
}
