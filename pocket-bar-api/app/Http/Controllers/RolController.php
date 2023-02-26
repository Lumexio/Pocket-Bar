<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Rol;
use App\Events\rolCreated;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|array|Rol
     */
    public function index(): Collection|array|Rol
    {
        return Rol::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Model|Rol|Collection
     */
    public function store(Request $request): Model|Rol|Collection
    {
        $rol = Rol::create($request->all());
        rolCreated::dispatch($rol);
        return $rol;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Collection|Model|Rol|Rol[]
     */
    public function show(int $id): array|Model|Collection|Rol
    {
        return Rol::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Collection|Model|Rol|Rol[]
     */
    public function update(Request $request, int $id): array|Model|Collection|Rol
    {
        $rol = Rol::find($id);
        $rol->update($request->all());
        return $rol;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return int
     */
    // public function destroy($id)
    // {
    //     return Rol::destroy($id);
    // }

    /**
     * @param $id
     * @return Collection|Model|Rol
     */
    public function activate($id): Model|Collection|Rol
    {
        $rol = Rol::find($id);
        $rol->update(['activo' => !$rol->activo]);
        return $rol;
    }
}
