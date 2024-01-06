<?php

namespace App\Http\Controllers;

use App\Models\TenantUser;
use Hash;
use Illuminate\Http\Request;
use Stripe\Stripe;

class TenantUserController extends Controller
{
    // agregar el metodo para crear, actualizar, eliminar, mostrar un registro y hacer login y logout de un usuario

    //metodo para crear un usuario
    public function store(Request $request)
    {
        //validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:tenant_users',
            'password' => 'required|string|max:255|min:8',
        ]);
        // crear el customer en stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripeCustomer = \Stripe\Customer::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        //crear el usuario
        $user = new TenantUser();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password =  $request->password;
        $user->customer_id = $stripeCustomer->id;
        $user->save();
        //retornar el usuario creado
        return response()->json($user, 201);
    }

    //metodo para actualizar un usuario
    public function update(Request $request)
    {
        $id = $request->user()->id;
        //validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenant_users,email,' . $id,
            'password' => 'nullable|string|max:255',
        ]);
        //buscar el usuario
        $user = TenantUser::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        //actualizar el usuario
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password =  $request->password;
        }
        $user->save();
        //retornar el usuario actualizado
        return response()->json($user, 200);
    }

    //metodo para eliminar un usuario
    public function destroy(Request $request)
    {
        $id = $request->user()->id;
        //buscar el usuario
        $user = TenantUser::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        //eliminar el token
        $user->tokens()->delete();
        //eliminar el usuario
        $user->delete();
        //retornar el usuario eliminado
        return response()->json(['message' => 'Usuario eliminado'], 200);
    }

    //metodo para mostrar un usuario
    public function show(Request $request)
    {
        $id = $request->user()->id;
        //buscar el usuario
        $user = TenantUser::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        //retornar el usuario
        return response()->json($user, 200);
    }

    //metodo para hacer login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        $user = TenantUser::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token
        ], 200);
    }

    //metodo para hacer logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesion cerrada'], 200);
    }
}
