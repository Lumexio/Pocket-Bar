<?php

namespace App\Http\Controllers;

use App\Enums\Rol;
use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;

class TenantController extends Controller
{
    // agregar el metodo para crear, actualizar, eliminar y mostrar registros de un usuario

    //metodo para crear un  Tenant
    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255|min:8',
            'branch_name' => 'required|string|max:255',
            'branch_address' => 'required|string|max:255',
            'branch_phone' => 'required|string|max:255',
        ]);
        // crear tenant
        $tenant = new Tenant();
        $tenant->tenantUser()->associate($request->user());
        $tenant->expiration_date = now()->addDays(30);
        $tenant->save();
        $tenant->domains()->create(['domain' => $request->domain]);
        // crear un usaurio en la app tenant
        Tenancy::initialize($tenant);

        $branch = new Branch();
        $branch->name = $request->branch_name;
        $branch->address = $request->branch_address;
        $branch->phone = $request->branch_phone;
        $branch->save();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $password = $request->password;
        $user->password = $password;
        $user->rol_id = Rol::Administrativo->value;
        $user->branch_id = $branch->id;
        $user->save();
        // volver a la app central
        Tenancy::end();


        //retornar el usuario creado
        return response()->json(
            [
                'message' => 'Tenant creado',
                'tenant' => $tenant,
                'domains' => $tenant->domains()->get(),
                'user_name' => $user->name,
                'user_email' => $user->email,
            ],
            201
        );
    }

    // metodo para agregar un dominio a un Tenant
    public function addDomain(Request $request, $id)
    {
        $request->validate([
            'domain' => 'required|string|max:20',
        ]);
        // buscar tenant
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return response()->json(['message' => 'Tenant no encontrado'], 404);
        }
        // agregar dominio
        $tenant->domains()->create(['domain' => $request->domain]);
        //retornar el usuario creado
        return response()->json(
            [
                'message' => 'Dominio agregado',
                'tenant' => $tenant,
                'domains' => $tenant->domains()->get()
            ],
            201
        );
    }

    //metodo para actualizar un Tenant
    public function update(Request $request, $id, $domainId)
    {
        //validar los datos
        $request->validate([
            'domain' => 'required|string|max:20',
        ]);
        //buscar el usuario
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return response()->json(['message' => 'Tenant no encontrado'], 404);
        }
        //actualizar el usuario
        $tenant->domains()->where('id', $domainId)->update(['domain' => $request->domain]);
        $tenant->save();
        $domains = $tenant->domains()->get();
        //retornar el usuario actualizado
        return response()->json(
            [
                'message' => 'Tenant actualizado',
                'tenant' => $tenant,
                'domains' => $domains
            ],
            200
        );
    }

    //metodo para eliminar un Tenant
    public function destroy($id)
    {
        //buscar el usuario
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return response()->json(['message' => 'Tenant no encontrado'], 404);
        }
        //eliminar el usuario
        $tenant->delete();
        //retornar el usuario eliminado
        return response()->json(['message' => 'Tenant eliminado'], 200);
    }

    //metodo para eliminar un dominio de un Tenant
    public function deleteDomain($id, $domainId)
    {
        //buscar el usuario
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return response()->json(['message' => 'Tenant no encontrado'], 404);
        }
        //eliminar el usuario
        $tenant->domains()->where('id', $domainId)->delete();
        //retornar el usuario eliminado
        return response()->json(['message' => 'Dominio eliminado'], 200);
    }

    //metodo para mostrar un Tenant
    public function show($id)
    {
        //buscar el usuario
        $tenant = Tenant::with('domains')->find($id);
        if (!$tenant) {
            return response()->json(['message' => 'Tenant no encontrado'], 404);
        }
        //retornar el usuario
        return response()->json(
            [
                'message' => 'Tenant encontrado',
                'tenant' => $tenant
            ],
            200
        );
    }

    //metodo para mostrar todos los Tenant de un usuario
    public function index(Request $request)
    {
        //buscar el usuario
        $tenants = $request->user()->tenants()->with('domains')->get();
        //retornar el usuario
        return response()->json(
            [
                'message' => 'Tenants encontrados',
                'tenants' => $tenants,
            ],
            200
        );
    }
}
