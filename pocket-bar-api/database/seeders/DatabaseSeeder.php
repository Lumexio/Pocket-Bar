<?php

namespace Database\Seeders;

use App\Enums\Rol;
use App\Models\Rol as ModelsRol;
use App\Models\Ticket;
use App\Models\TicketDetail;
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
        /**
         * *Status de articulos
         */
        DB::table('status_tbl')->insert([
            'nombre_status' => 'Disponible',
        ]);
        DB::table('status_tbl')->insert([
            'nombre_status' => 'Agotado',
        ]);
        DB::table('status_tbl')->insert([
            'nombre_status' => 'En uso',
        ]);



        \App\Models\User::factory(10)->create();


        /**
         * *Cuentas de usuario de prueba
         * ! La correción se comporta extraño, por lo que se optó por que se mantenga el no código comentado
         */
        // foreach (Rol::toArray() as $rol) {
        //     $user = new User([
        //         'name' => $rol->name,
        //         'email' => $rol->name . '@' . $rol->name . '.com',
        //         'password' => Hash::make('12345678'),
        //         'rol_id' => $rol->value,
        //     ]);
        //     $user->save();
        // }
        foreach (Rol::toArray() as $rol) {
            DB::table('users')->insert([
                'name' => $rol->name,
                'email' => $rol->name . '@' . $rol->name . '.com',
                'password' => Hash::make('12345678'),
                'rol_id' => $rol->value,
            ]);
        }


        /**
         * [Insersiones a categorias]
         */
        DB::table('categorias_tbl')->insert([
            'nombre_categoria' => 'Plomería',
            'descripcion_categoria' => 'Categoria de plomería',
        ]);
        DB::table('categorias_tbl')->insert([
            'nombre_categoria' => 'Electrícidad',
            'descripcion_categoria' => 'Categoria de electrícidad',
        ]);
        DB::table('categorias_tbl')->insert([
            'nombre_categoria' => 'General',
            'descripcion_categoria' => 'Categoria de uso general',
        ]);






        DB::table('marcas_tbl')->insert([
            'nombre_marca' => 'Honda',
            'descripcion_marca' => 'Marca de motocicletas y autos',
        ]);
        DB::table('marcas_tbl')->insert([
            'nombre_marca' => 'Yamaha',
            'descripcion_marca' => 'Marca de motocicletas',
        ]);
        DB::table('marcas_tbl')->insert([
            'nombre_marca' => 'Asus',
            'descripcion_marca' => 'Marca de computadoras',
        ]);


        DB::table('tipos_tbl')->insert([
            'nombre_tipo' => 'Consumible',
            'descripcion_tipo' => 'Productos de consumo diario',
        ]);
        DB::table('tipos_tbl')->insert([
            'nombre_tipo' => 'Herramienta',
            'descripcion_tipo' => 'Herraientas de trabajo',
        ]);
        DB::table('tipos_tbl')->insert([
            'nombre_tipo' => 'General',
            'descripcion_tipo' => 'Productos de uso general',
        ]);


        DB::table('proveedores_tbl')->insert([
            'nombre_proveedor' => 'Davila',
            'descripcion' => 'Proveedor de productos de plomería',
        ]);
        DB::table('proveedores_tbl')->insert([
            'nombre_proveedor' => 'Ortíz',
            'descripcion' => 'Proveedor de productos de electrícidad',
        ]);
        DB::table('proveedores_tbl')->insert([
            'nombre_proveedor' => 'Desconocido',
            'descripcion' => 'Proveedor de productos de uso general',
        ]);

        //Mesas
        DB::table("mesas_tbl")->insert([
            "nombre_mesa" => "barra",
            'descripcion_mesa' => 'Mesa de barra',
        ]);
        for ($i = 1; $i < 11; $i++) {
            DB::table("mesas_tbl")->insert([
                "nombre_mesa" => $i,
                'descripcion_mesa' => 'Mesa ' . $i,
            ]);
        }

        Workshift::create([
            "active" => 1,
            "start_money" => 1000,
        ]);

        \App\Models\Articulo::factory(10)->create();

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
