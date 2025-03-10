<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request){
        $validator = validator()->make($request->all(), [
            "page" => "required|min:1|int",
            "limit" => "required|int|min:1|max:100",
            "search_params" => "required|string",
            "category" => "required|string",
        ]);
        
        $data = $request->only(["page", "limit", "search_params", "category"]);

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
                        case "category":
                            $data["category"] = "";
                            break;
                }
            }
        }

        $qb = Product::whereRaw("CONCAT(name, description) LIKE ?",  ["%" . $data["search_params"] . "%"]);

        $categories = DB::table("products")->distinct()->select("category")->get()->toArray();

        $total = $qb->count();
        
        if(!empty($data["category"]))
            $qb->where("category", $data["category"]);

        $products = $qb->limit($data["limit"])->offset(($data["page"] - 1) * $data["limit"])->get()->toArray();

        return Inertia::render("products/Index", [
            "products"=> $products,
            "others" => [
                "total" => $total,
                "limit" => $data["limit"],
                "page" => $data["page"],
                "search_params" => $data["search_params"],
                "categories" => $categories,
                "category" => $data["category"]
            ]
        ]);
    }

    public function destroy(Product $product){
        $product->delete();

        return to_route("products");
    }
}
