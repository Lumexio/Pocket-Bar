<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;

use App\Events\marcaCreated;
use App\Http\Requests\MarcaValidationRequest;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Marca::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MarcaValidationRequest $request)
    {
        if (Marca::where('nombre_marca', '=', $request->get('nombre_marca'))->exists()) {
            return response([
                'message' => ['Uno de los parametros ya exite.']
            ], 409);
        } else {
            $marca = Marca::create($request->all());
            marcaCreated::dispatch($marca);
            return $marca;
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
        return Marca::find($id);
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
        $marca = Marca::find($id);
        $marca->update($request->all());
        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Marca::destroy($id);
    }
}
