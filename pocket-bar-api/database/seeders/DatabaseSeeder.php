<?php

namespace Database\Seeders;

use App\Enums\Rol;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Rol as ModelsRol;
use App\Models\Table;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\Type;
use App\Models\User;
use App\Models\Workshift;
use Database\Factories\TicketFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'name' => 'Sucursal 1',
            'address' => 'Calle 1',
        ]);

        Branch::create([
            'name' => 'Sucursal 2',
            'address' => 'Calle 2',
        ]);

        /**
         * *Status de articulos
         */
        DB::table('statuses')->insert([
            'name' => 'Disponible',
        ]);
        DB::table('statuses')->insert([
            'name' => 'Agotado',
        ]);
        DB::table('statuses')->insert([
            'name' => 'En uso',
        ]);

        Currency::create([
            "code" => "MXN",
            "default" => true,
            "rate" => 1,
        ]);

        Currency::create([
            "code" => "USD",
            "default" => false,
            "rate" => 20,
        ]);

        \App\Models\User::factory(10)->create();

        /**
         * *Roles
         */
        foreach (Rol::toArray() as $rol) {
            $user = new User();
            $user->name = $rol->name;
            $user->email = $rol->name . '@' . $rol->name . '.com';
            $user->password = "12345678";
            $user->rol_id = $rol->value;
            $user->branch_id = 1;
            $user->save();
        }


        /**
         * [Insersiones a categorias]
         */
        Category::create([
            'name' => 'Plomería',
            'description' => 'Categoria de plomería',
        ]);
        Category::create([
            'name' => 'Electrícidad',
            'description' => 'Categoria de electrícidad',
        ]);
        Category::create([
            'name' => 'General',
            'description' => 'Categoria de uso general',
        ]);

        Brand::create([
            'name' => 'Honda',
            'description' => 'Marca de motocicletas y autos',
        ]);
        Brand::create([
            'name' => 'Yamaha',
            'description' => 'Marca de motocicletas',
        ]);
        Brand::create([
            'name' => 'Asus',
            'description' => 'Marca de computadoras',
        ]);

        Type::create([
            'name' => 'Consumible',
            'description' => 'Productos de consumo diario',
        ]);
        Type::create([
            'name' => 'Herramienta',
            'description' => 'Herraientas de trabajo',
        ]);
        Type::create([
            'name' => 'General',
            'description' => 'Productos de uso general',
        ]);

        Provider::create([
            'name' => 'Honda',
            'description' => 'Proveedor de motocicletas y autos',
        ]);
        Provider::create([
            'name' => 'Yamaha',
            'description' => 'Proveedor de motocicletas',
        ]);
        Provider::create([
            'name' => 'Asus',
            'description' => 'Proveedor de computadoras',
        ]);

        //Mesas
        Table::create([
            "name" => "barra",
            'description' => 'Mesa de barra',
            "branch_id" => 1,
        ]);
        for ($i = 1; $i < 11; $i++) {
            Table::create([
                "name" => "mesa " . $i,
                'description' => 'Mesa ' . $i,
                "branch_id" => 1,
            ]);
        }

        Workshift::create([
            "active" => 1,
            "start_money" => 1000,
            "branch_id" => 1,
        ]);

        \App\Models\Product::factory(10)->create();

        foreach (Branch::all() as $branch) {
            Product::all()->each(function ($product) use ($branch) {
                $branch->stock()->create([
                    "product_id" => $product->id,
                    "stock" => rand(0, 100),
                ]);
            });
        }

        $tickets = TicketFactory::new()->times(10)->raw();

        foreach ($tickets as $ticket) {
            $ticketToInsert = $ticket;
            unset($ticketToInsert["items"]);

            $id = Ticket::insertGetId($ticketToInsert);
            foreach ($ticket["items"] as $item) {
                $item["ticket_id"] = $id;
                TicketDetail::insert($item);
            }
        }

        for ($i = 1; $i < 11; $i++) {
            $monto = rand(100, 1000);
            if (rand(0, 1) == 0) {
                $rol = ModelsRol::find(Rol::Guardia->value);
                $descripcion = "Cover";
            } else {
                $rol = ModelsRol::find(Rol::Cajero->value);
                if (rand(0, 1) == 0) {
                    $monto = $monto * -1;
                    $descripcion = "Salida general";
                } else {
                    $descripcion = "Ingreso general";
                }
            }
            $user = $rol->users()->first();
            $user->generalIncomings()->create([
                "workshift_id" => 1,
                "amount" => $monto,
                "description" => $descripcion,
            ]);
        }
    }
}
