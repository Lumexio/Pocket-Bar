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
use App\Http\Requests\ListRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserValidationRequest;
use App\Http\Requests\UsuarioValidationRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {

        $loggeduser = Auth::id();
        $showActive = $request->get('showActive');
        $users = DB::table('users')->where('users.id', '!=', $loggeduser)
            ->where("users.branch_id", "=", $request->input("branch_id", Auth::user()->branch_id))
            ->leftJoin('rols', 'users.rol_id', '=', 'rols.id')
            ->select('users.id', 'users.name', 'users.active', 'users.email', 'users.password', 'users.nominas', 'rols.name as name_rol');
        if (isset($showActive)) {
            $users = $users->where('users.active', '=', $showActive);
        }
        return response()->json([
            'message' => 'success',
            'users' => $users->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserValidationRequest $request
     * @return JsonResponse
     */
    public function store(UserValidationRequest $request): JsonResponse
    {
        $user = User::where("name", $request->input("name"))->where("active", true)->first();
        if (!empty($user)) {
            return response()->json(
                [
                    'message' => 'El usuario ya existe.'
                ],
                409
            );
        }
        $user = new User();
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = $request->input("password");
        $user->rol_id = $request->input("rol_id");
        $user->branch_id = $request->input("branch_id", auth()->user()->branch_id);
        $user->save();
        try {
            broadcast((new userCreated())->broadcastToEveryone());
            userCreated::dispatch($user);
        } catch (\Throwable) {
        }
        return response()->json([
            'message' => 'success',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = User::where("branch_id", $request->input("branch_id", auth()->user()->branch_id))->find($id);
        if (empty($user)) {
            return response()->json(
                [
                    'message' => 'El usuario no existe.'
                ],
                404
            );
        }
        return response()->json([
            'message' => 'success',
            'user' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id, UserUpdateRequest $request): JsonResponse
    {
        $user = User::find($id);
        $user2 = User::where("name", $request->input("name"))->where("active", true)->first();
        if (!empty($user2) && $user2->id != $user->id) {
            return response()->json(
                [
                    'message' => 'El usuario ya existe.'
                ],
                409
            );
        }
        if (empty($user)) {
            return response()->json(
                [
                    'message' => 'El usuario no existe.'
                ],
                404
            );
        }
        $user->update($request->all());
        try {
            broadcast((new userCreated())->broadcastToEveryone());
            userCreated::dispatch($user);
        } catch (\Throwable) {
        }
        return response()->json([
            'message' => 'success',
            'user' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse

     */
    public function activate(Request $request, int $id): JsonResponse
    {
        $user = User::where("branch_id", $request->input("branch_id", auth()->user()->branch_id))->find($id);
        if (empty($user)) {
            return response()->json(
                [
                    'message' => 'El usuario no existe.'
                ],
                404
            );
        }
        $user->active = !$user->active;
        $user->save();
        broadcast((new userCreated())->broadcastToEveryone());
        userCreated::dispatch($user);
        // try {

        // } catch (\Exception) {
        // }
        return response()->json([
            'message' => 'success',
            'user' => $user
        ], 200);
    }


    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('name', $request->name)->where("active", true)->first();

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
