<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketDetail;
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
         * *Rol de usuarios
         */
        DB::table('rols_tbl')->insert([
            'name_rol' => 'Administrativo',
        ]);
        DB::table('rols_tbl')->insert([
            'name_rol' => 'Gerencia',
        ]);
        DB::table('rols_tbl')->insert([
            'name_rol' => 'Cajer@',
        ]);
        DB::table('rols_tbl')->insert([
            'name_rol' => 'Meser@',
        ]);
        DB::table('rols_tbl')->insert([
            'name_rol' => 'Bartender',
        ]);
        DB::table('rols_tbl')->insert([
            'name_rol' => 'Intendencia',
        ]);
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
        /**
         * *Ubicaciones de prueba
         */
        DB::table('travesano_tbl')->insert([
            'nombre_travesano' => '1',
        ]);
        DB::table('rack_tbl')->insert([
            'nombre_rack' => 'A',
        ]);
        DB::table('travesano_tbl')->insert([
            'nombre_travesano' => '2',
        ]);
        DB::table('rack_tbl')->insert([
            'nombre_rack' => 'B',
        ]);


        \App\Models\User::factory(10)->create();


        /**
         * *Cuentas de usuario de prueba
         */
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'a@a.com',
            'password' => Hash::make('12345678'),
            'rol_id' => '1',
        ]);
        DB::table('users')->insert([
            'name' => 'adminb',
            'email' => 'ab@ab.com',
            'password' => Hash::make('12345678'),
            'rol_id' => '2',
        ]);
        DB::table('users')->insert([
            'name' => 'cajero',
            'email' => 'c@c.com',
            'password' => Hash::make('12345678'),
            'rol_id' => '3',
        ]);
        DB::table('users')->insert([
            'name' => 'mesero',
            'email' => 'm@m.com',
            'password' => Hash::make('12345678'),
            'rol_id' => '4',
        ]);

        DB::table('users')->insert([
            'name' => 'barra',
            'email' => 'b@b.com',
            'password' => Hash::make('12345678'),
            'rol_id' => '5',
        ]);



        /**
         * [Insersiones a categorias]
         */
        DB::table('categorias_tbl')->insert([
            'nombre_categoria' => 'Plomería',
        ]);
        DB::table('categorias_tbl')->insert([
            'nombre_categoria' => 'Electrícidad',
        ]);
        DB::table('categorias_tbl')->insert([
            'nombre_categoria' => 'General',
        ]);


        DB::table('marcas_tbl')->insert([
            'nombre_marca' => 'Honda',
        ]);
        DB::table('marcas_tbl')->insert([
            'nombre_marca' => 'Yamaha',
        ]);
        DB::table('marcas_tbl')->insert([
            'nombre_marca' => 'Asus',
        ]);


        DB::table('tipos_tbl')->insert([
            'nombre_tipo' => 'Consumible',
        ]);
        DB::table('tipos_tbl')->insert([
            'nombre_tipo' => 'Herramienta',
        ]);
        DB::table('tipos_tbl')->insert([
            'nombre_tipo' => 'General',
        ]);


        DB::table('proveedores_tbl')->insert([
            'nombre_proveedor' => 'Davila',
        ]);
        DB::table('proveedores_tbl')->insert([
            'nombre_proveedor' => 'Ortíz',
        ]);
        DB::table('proveedores_tbl')->insert([
            'nombre_proveedor' => 'Desconocido',
        ]);

        //Mesas

        for ($i = 1; $i < 11; $i++) {
            DB::table("tables")->insert([
                "name" => $i,
            ]);
        }

        DB::table("workshifts")->insert([
            "active" => 1,
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
    }
}
