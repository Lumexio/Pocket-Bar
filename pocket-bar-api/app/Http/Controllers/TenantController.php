<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    // agregar el metodo para crear, actualizar, eliminar y mostrar registros de un usuario

    //metodo para crear un  Tenant
    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:20',
        ]);
        // crear tenant
        $tenant = new Tenant();
        $tenant->tenantUser()->associate($request->user());
        $tenant->expiration_date = now()->addDays(30);
        $tenant->save();
        $tenant->domains()->create(['domain' => $request->domain]);
        //retornar el usuario creado
        return response()->json(
            [
                'message' => 'Tenant creado',
                'tenant' => $tenant,
                'domains' => $tenant->domains()->get()
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
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return response()->json(['message' => 'Tenant no encontrado'], 404);
        }
        $domains = $tenant->domains()->get();
        //retornar el usuario
        return response()->json(
            [
                'message' => 'Tenant encontrado',
                'tenant' => $tenant,
                'domains' => $domains
            ],
            200
        );
    }

    //metodo para mostrar todos los Tenant de un usuario
    public function index(Request $request)
    {
        //buscar el usuario
        $tenants = $request->user()->tenants()->get();
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
