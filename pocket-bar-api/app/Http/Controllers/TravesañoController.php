<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travesaño;
use App\Events\travesañoCreated;
use App\Http\Requests\TravesanoValidationRequest;

class TravesañoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $travesaño =  Travesaño::all();
        return $travesaño;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TravesanoValidationRequest $request)
    {
        if (Travesaño::where('nombre_travesano', '=', $request->get('nombre_travesano'))->exists()) {
            return response([
                'message' => ['Uno de los parametros ya exite.']
            ], 409);
        } else {
            $travesaño = Travesaño::create($request->all());
            travesañoCreated::dispatch($travesaño);
            return $travesaño;
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
        return Travesaño::find($id);
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
        $travesaño = Travesaño::find($id);
        $travesaño->update($request->all());
        return $travesaño;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $travesaño = Travesaño::destroy($id);
        return $travesaño;
    }
}
