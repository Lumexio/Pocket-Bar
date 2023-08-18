<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        return response()->json([
            "message" => "success",
            "branches" => Branch::all()
        ], 200);
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = new Branch();
        $branch->name = $request->name;
        $branch->address = $request->address;
        $branch->phone = $request->phone;
        $branch->timetable = $request->timetable;
        $branch->save();
        return response()->json([
            "message" => "Branch created successfully",
            "branch" => $branch
        ], 201);
    }

    public function show($id)
    {
        $branch = Branch::find($id);
        if ($branch) {
            return response()->json([
                "message" => "success",
                "branch" => $branch
            ], 200);
        } else {
            return response()->json([
                "message" => "Branch not found"
            ], 404);
        }
    }

    public function update(StoreBranchRequest $request, $id)
    {
        $branch = Branch::find($id);
        if ($branch) {
            $branch->name = $request->name;
            $branch->address = $request->address;
            $branch->phone = $request->phone;
            $branch->timetable = $request->timetable;
            $branch->save();
            return response()->json([
                "message" => "Branch updated successfully",
                "branch" => $branch
            ], 200);
        } else {
            return response()->json([
                "message" => "Branch not found"
            ], 404);
        }
    }

    public function activate($id)
    {
        $branch = Branch::find($id);
        if ($branch) {
            $branch->status = !$branch->status;
            $branch->save();
            return response()->json([
                "message" => "Branch activated successfully",
                "branch" => $branch
            ], 200);
        } else {
            return response()->json([
                "message" => "Branch not found"
            ], 404);
        }
    }
}
