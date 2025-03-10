<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = validator()->make($request->all(), [
            "page" => "required|min:1|int",
            "limit" => "required|int|min:1|max:100",
            "search_params" => "required|string",
        ]);
        
        $data = $request->only(["page", "limit", "search_params"]);

        if($validator->fails()){
            foreach($validator->errors()->toArray() as $key => $value){
                switch($key){
                    case "page":
                        $data["page"] = 1;
                        break;
                    case "limit":
                        $data["limit"] = 10;
                        break;
                    case "search_params":
                        $data["search_params"] = "";
                        break;
                }
            }
        }
        
        $qb = Product::whereRaw("CONCAT(name, description) LIKE ?",  ["%" . $data["search_params"] . "%"]);

        $total = $qb->count();
        $products = $qb->limit($data["limit"])->offset(($data["page"] - 1) * $data["limit"])->get()->toArray();
        
        return $this->Ok($products, "Products has been retrieved!", [
            "total" => $total,
            "limit" => $data["limit"],
            "page" => $data["page"],
            "search_params" => $data["search_params"],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
