<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\mesaCreated;
use App\Http\Requests\MesaValidationRequest;
use App\Models\Mesa;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Mesa::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MesaValidationRequest $request)
    {
        if (Mesa::where('nombre_mesa', '=', $request->get('nombre_mesa'))->exists()) {
            return response([
                'message' => ['Nombre el nombre de la mesa  ya exite.']
            ], 409);
        } else {
            $mesa = Mesa::create($request->all());
            mesaCreated::dispatch($mesa);
            return $mesa;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return Mesa::find($id);
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
        $mesa = Mesa::find($id);
        $mesa->update($request->all());
        return $mesa;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        return Mesa::destroy($id);
    }
}
