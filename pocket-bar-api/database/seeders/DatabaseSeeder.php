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

        DB::table('users')->insert([
            'name' => 'barra2',
            'email' => 'b2@b.com',
            'password' => Hash::make('12345678'),
            'rol_id' => '5',
        ]);



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
