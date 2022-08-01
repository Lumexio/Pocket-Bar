<?php

namespace App\Http\Controllers;

use App\Models\Workshift;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function getMustBe()
    {
        $actualWorkshift = Workshift::where('active', 1)->first();
    }

    public function close()
    {
        # code...
    }
}
