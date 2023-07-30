<?php

namespace App\Http\Controllers;

use App\Events\ProductCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ListRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\ProductValidationRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $showActive = $request->get('showActive');
        $dat = DB::table('products as art')
            ->leftJoin('users', 'art.user_id', '=', 'users.id')
            ->leftJoin('categories as cat', 'art.category_id', '=', 'cat.id')
            ->leftJoin('brands', 'art.brand_id', '=', 'brands.id')
            ->leftJoin('providers as prov', 'art.provider_id', '=', 'prov.id')
            ->leftJoin('statuses', 'art.status_id', '=', 'statuses.id')
            ->leftJoin('types', 'art.type_id', '=', 'types.id')
            ->join("stocks", "art.id", "=", "stocks.product_id")
            ->where("stocks.branch_id", "=", $request->input("branch_id", Auth::user()->branch_id));
        if (isset($showActive)) {
            $dat = $showActive ? $dat->whereNull('stocks.deactivated_at') : $dat->whereNotNull('stocks.deactivated_at');
        }
        $dat = $dat->select('art.id', 'art.name', 'stocks.units', 'art.price', 'art.description', 'art.image', 'users.name', 'cat.name', 'brands.name', 'prov.name', 'statuses.name', 'types.name', "stocks.deactivated_at")
            ->get()
            ->map(
                function ($item) {
                    $item->image = url("images/$item->image");
                    return $item;
                }
            );
        return response()->json(["message" => "success", "articulos" => $dat]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ProductValidationRequest $request
     * @return JsonResponse
     *
     * dentro hay una validacion para saber si el nombre del articulo ya exite en los registros.
     */
    public function store(ProductValidationRequest $request): JsonResponse
    {
        if (Product::where('name', '=', $request->name)->exists()) {
            return response()->json(
                [
                    'message' => ["Ya hay un producto existente con este nombre {$request->name}."]
                ],
                409
            );
        } else {
            $photo = $request->file('image');
            $newProduct = new Product();
            $newProduct->name = $request->name;
            $newProduct->description = $request->description ?? null;
            $newProduct->price = $request->price;
            $newProduct->category_id = $request->category_id;
            $newProduct->brand_id = $request->brand_id;
            $newProduct->provider_id = $request->provider_id ?? null;
            $newProduct->status_id = $request->status_id;
            $newProduct->type_id = $request->type_id;
            $newProduct->user_id = Auth::id();
            if (isset($photo)) {
                $extension = $photo->guessExtension();
                $name_foto =  $request->nombre_articulo . '.' . $extension;
                $request->foto_articulo->move(public_path('images'), $name_foto);
                $newProduct->image = $name_foto;
            }
            $newProduct->save();
            Branch::all()->each(function ($branch) use ($newProduct, $request) {
                $branch->stock()->create([
                    'product_id' => $newProduct->id,
                    'units' => $branch->id == $request->user()->branch_id ? $request->units : 0,
                ]);
            });
            try {
                broadcast((new ProductCreated())->broadcastToEveryone());
            } catch (\Exception) {
            }
            return response()->json(["message" => "success", "articulo" => $newProduct], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::find($id);
        $units = $product->stock()->where("branch_id", "=", Auth::user()->branch_id)->first()->units;
        if (empty($product)) {
            return response()->json(["message" => "Articulo no encontrado"], 404);
        }
        $product = $product->toArray();
        return response()->json(["message" => "success", "articulo" => [...$product, "units" => $units]], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        $product = Product::find($id);
        if (empty($product)) {
            return response()->json(["message" => "Articulo no encontrado"], 404);
        }
        if (isset($request->image)) {
            $newName = $request->name;
            $filename = $product->image;
            //lugar donde esta guardado el archivo existente
            $oldPath = public_path("/images/$filename");
            $filename =  $newName . '.' . "jpg";
            $newPath = public_path("/images/$filename");
            rename($oldPath, $newPath);
            $product->image = $filename;
        }
        $product->update($request->all());
        $product->user_id = Auth::id();
        if (isset($request->units)) {
            $stock = Stock::where("product_id", "=", $product->id)->where("branch_id", "=", Auth::user()->branch_id)->first();
            $stock->units = $request->units;
            $stock->save();
        }
        $product->save();
        try {
            broadcast((new ProductCreated())->broadcastToEveryone());
        } catch (\Exception) {
        }
        return response()->json(["message" => "success", "articulo" => $product], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function activate(Request $request, int $id): JsonResponse
    {
        $request->validate([
            "branch_id" => "nullable|exists:branches,id"
        ]);
        $articulo = Stock::where("product_id", "=", $id)->where("branch_id", "=", $request->input("branch_id", Auth::user()->branch_id))->first();
        if (isset($articulo->deactivated_at)) {
            $articulo->deactivated_at = null;
            $message = "Articulo Activado";
        } else {
            $articulo->deactivated_at = now();
            $message = "Articulo Desactivado";
        }
        $articulo->save();
        try {
            broadcast((new Product())->broadcastToEveryone());
        } catch (\Exception) {
        }
        return response()->json(['message' => $message, 'articulo' => $articulo]);
    }
}
