<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use File;
use App\Events\articuloCreated;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    public function updatephoto(Request $request, $id)
    {
        /**
         * * Esta pieza de codigo esta sujeta a cambios para detectar de que modelo proviene
         * !Obtendra el parametro extra llamado 'from' para esta tarea y crear una imagen para el modelo al que apunta.
         */
        $articulo = Articulo::find($id);
        $filename = $articulo->foto_articulo;
        if ($filename != null) {
            $path = public_path("/images/$filename");
            File::delete($path);
        }
        $extension = $request->file('foto_articulo')->guessExtension();
        if ($extension === 'jpg' || $extension === 'png') {
            $name_foto =  $articulo->nombre_articulo . '.' . $extension;
        } else if ($extension != 'jpg' || $extension != 'png') {
            $name_foto =  $articulo->nombre_articulo . '.' . 'jpg';
        }
        $request->foto_articulo->move(public_path('images'), $name_foto);
        $articulo["foto_articulo"] = $name_foto;
        $articulo->save();
        articuloCreated::dispatch($articulo);
        return $articulo;
    }
}
