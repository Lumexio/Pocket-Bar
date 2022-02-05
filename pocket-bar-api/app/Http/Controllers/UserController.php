<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Events\userCreated;
use App\Http\Requests\UsuarioValidationRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dat = DB::table('users')->leftJoin('rols_tbl', 'users.rol_id', '=', 'rols_tbl.id')->select('users.id', 'users.name', 'users.email', 'users.password', 'rols_tbl.name_rol')->get();
        return $dat;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuarioValidationRequest $request)
    {
        $user = User::create($request->all());
        userCreated::dispatch($user);
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        userCreated::dispatch($user);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::destroy($id);
    }


    function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('name', $request->name)->first();

        // print_r($data);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['Las credentials no concuerdan con ningun registro.']
            ], 404);
        }


        if (Auth::attempt($credentials)) {
            $token = $user->createToken('my-app-token')->plainTextToken;
            //Auth::setUser($user);
            auth()->setUser($user);
            $request->session()->regenerate();
            $response = [
                'user' => $user,
                'token' => $token,
            ];

            Auth::login($user, true);
            $request->session()->save();
            return response($response, 200);
        }
    }
}
