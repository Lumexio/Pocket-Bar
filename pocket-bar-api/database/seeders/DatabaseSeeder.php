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
use App\Models\TenantUser;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\User;
use App\Models\Workshift;
use Database\Factories\TicketFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // empezamos por crear un tenant user
        $user = new TenantUser([
            'name' => 'admin',
            'email' => 'admin123@gmail.com',
            'password' => '12345678',
        ]);
        $user->save();

        // creamos un tenant
        $tenant = new \App\Models\Tenant([
            'tenant_user_id' => $user->id,
        ]);
        $tenant->save();

        // creamos un dominio para el tenant
        $domain = $tenant->domains()->create([
            'domain' => 'admin.localhost',
        ]);

        // Inicializamos el tenant
        tenancy()->initialize($tenant);


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
        // DB::table('statuses')->insert([
        //     'name' => 'Disponible',
        // ]);
        // DB::table('statuses')->insert([
        //     'name' => 'Agotado',
        // ]);
        // DB::table('statuses')->insert([
        //     'name' => 'En uso',
        // ]);

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
        $jugoCat = new Category([
            'name' => 'Jugo',
            'description' => 'Jugos de frutas naturales',
        ]);
        $jugoCat->save();

        $jugoCat->types()->create([
            'name' => 'Naranja',
            'description' => 'Jugo de naranja natural',
        ]);
        $jugoCat->types()->create([
            'name' => 'Manzana',
            'description' => 'Jugo de manzana natural',
        ]);

        $licorCat = new Category([
            'name' => 'Licor',
            'description' => 'Bebidas alcoholicas',
        ]);
        $licorCat->save();

        $licorCat->types()->create([
            'name' => 'Vodka',
            'description' => 'Bebida alcoholica de vodka',
        ]);
        $licorCat->types()->create([
            'name' => 'Tequila',
            'description' => 'Bebida alcoholica de tequila',
        ]);
        $licorCat->types()->create([
            'name' => 'Whisky',
            'description' => 'Bebida alcoholica de whisky',
        ]);

        $cervezaCat = new Category([
            'name' => 'Cerveza',
            'description' => 'Cervezas nacionales e importadas',
        ]);
        $cervezaCat->save();

        $cervezaCat->types()->create([
            'name' => 'Clara',
            'description' => 'Cerveza clara',
        ]);

        $cervezaCat->types()->create([
            'name' => 'Oscura',
            'description' => 'Cerveza oscura',
        ]);

        $cervezaCat->types()->create([
            'name' => 'Artesanal',
            'description' => 'Cerveza artesanal',
        ]);

        $refrescoCat = new Category([
            'name' => 'Refresco',
            'description' => 'Refrescos de cola y saborizados',
        ]);
        $refrescoCat->save();

        $refrescoCat->types()->create([
            'name' => 'Cola',
            'description' => 'Refresco de cola',
        ]);

        $refrescoCat->types()->create([
            'name' => 'Saborizado',
            'description' => 'Refresco saborizado',
        ]);

        Brand::create([
            'name' => 'Coca Cola',
            'description' => 'Marca de refrescos',
        ]);

        Brand::create([
            'name' => 'Pepsi',
            'description' => 'Marca de refrescos',
        ]);

        Brand::create([
            'name' => 'Modelo',
            'description' => 'Marca de cerveza',
        ]);

        Brand::create([
            'name' => 'Corona',
            'description' => 'Marca de cerveza',
        ]);

        Brand::create([
            'name' => 'Jose Cuervo',
            'description' => 'Marca de tequila',
        ]);

        Brand::create([
            'name' => 'Absolut',
            'description' => 'Marca de vodka',
        ]);

        Brand::create([
            'name' => 'Jack Daniels',
            'description' => 'Marca de whisky',
        ]);

        Brand::create([
            'name' => 'Jumex',
            'description' => 'Marca de jugos',
        ]);

        Provider::create([
            'name' => 'Coca Cola',
            'description' => 'Proveedor de refrescos',
        ]);

        Provider::create([
            'name' => 'Pepsi',
            'description' => 'Proveedor de refrescos',
        ]);

        Provider::create([
            'name' => 'Modelo',
            'description' => 'Proveedor de cerveza',
        ]);

        Provider::create([
            'name' => 'Corona',
            'description' => 'Proveedor de cerveza',
        ]);

        Provider::create([
            'name' => 'Jose Cuervo',
            'description' => 'Proveedor de tequila',
        ]);

        Provider::create([
            'name' => 'Absolut',
            'description' => 'Proveedor de vodka',
        ]);

        Provider::create([
            'name' => 'Jack Daniels',
            'description' => 'Proveedor de whisky',
        ]);

        Provider::create([
            'name' => 'Jumex',
            'description' => 'Proveedor de jugos',
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

        // crear los productos y sus variantes
        Product::factory(10)->create();

        foreach (Product::all() as $product) {
            $product->productVariants()->create([
                "price" => 100,
                "presentation" => 1,
                "unit" => "UNIT",
                "user_id" => 1,
            ]);
            $product->productVariants()->create([
                "price" => 200,
                "presentation" => 1,
                "unit" => "UNIT",
                "user_id" => 1,
            ]);
        }

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

        tenancy()->end();
    }
}
