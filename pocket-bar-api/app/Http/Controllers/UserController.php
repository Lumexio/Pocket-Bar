<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Events\userCreated;
use App\Http\Requests\UsuarioValidationRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Collection
     */
    public function index(): Collection
    {
        $loggeduser = Auth::id();
        return DB::table('users')->where('users.id', '!=', $loggeduser)->leftJoin('rols_tbl', 'users.rol_id', '=', 'rols_tbl.id')->select('users.id', 'users.name', 'users.email', 'users.password', 'users.nominas', 'rols_tbl.name_rol')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UsuarioValidationRequest $request
     * @return User
     */
    public function store(UsuarioValidationRequest $request): User
    {
        $user = User::create($request->all());
        userCreated::dispatch($user);
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return User
     */
    public function show(int $id): User
    {
        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return User
     */
    public function update(Request $request, int $id): User
    {
        $user = User::find($id);
        $user->update($request->all());
        userCreated::dispatch($user);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return User
     */
    public function activate(int $id): User
    {
        $user = User::find($id);
        $user->active = !$user->active;
        $user->save();
        return $user;
    }


    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('name', $request->name)->where("active", true)->first();

        // print_r($data);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'message' => ['Las credentials no concuerdan con ningun registro.']
                ],
                404
            );
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
            return response()->json($response);
        }
    }
    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out'], 200);
    }
}
