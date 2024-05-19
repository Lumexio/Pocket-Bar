<?php

namespace App\Http\Controllers;

use App\Events\ProductCreated;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use File;
use Illuminate\Http\JsonResponse;

class PhotoController extends Controller
{
    public function updatephoto(Request $request, $id): JsonResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required|string|in:product,product_variant'
        ]);
        if ($request->type == 'product') {
            $product = Product::with('photo')->find($id);
        } else {
            $product = ProductVariant::with('photo')->find($id);
        }

        if ($product->photo) {
            File::delete(storage_path('app/public/' . $product->photo->path));
            $product->photo->delete();
        }

        $photo = $request->file('photo');

        $path = $photo->store(storage_path('app/public'));

        $product->photo()->create([
            'name' => $photo->getClientOriginalName(),
            'path' => $path
        ]);

        if ($request->type == 'product') {
            event(new ProductCreated($product));
        }

        return response()->json(["message" => "success"], 200);
    }
}
