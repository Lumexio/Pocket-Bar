<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Status;
use App\Events\statusCreated;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Status|array|Collection
     */
    public function index(): Status|array|Collection
    {
        return Status::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Model|Status|Collection
     */
    public function store(Request $request): Model|Status|Collection
    {
        $status = Status::create($request->all());
        statusCreated::dispatch($status);
        return $status;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Status|Model|Collection
     */
    public function show(int $id): Status|Model|Collection
    {
        return Status::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Model|Collection|Status
     */
    public function update(Request $request, int $id): Model|Collection|Status
    {
        $status = Status::find($id);
        $status->update($request->all());
        statusCreated::dispatch($status);
        return $status;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    // public function destroy($id)
    // {
    //     return Status::destroy($id);
    // }

    /**
     * Activate the specified resource.
     *
     * @param int $id
     * @return Status
     */
    public function activate(int $id): Status
    {
        $status = Status::find($id);
        $status->status = !$status->status;
        $status->save();
        return $status;
    }
}
