<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        return $this->Ok($products, "Products has been retrieved!", [
            "total" => $total,
            "limit" => $data["limit"],
            "page" => $data["page"],
            "search_params" => $data["search_params"],
            "categories" => $categories,
            "category" => $data["category"]
        ]);
    }


    public function checkStepOne(Request $request){
        $validator = validator()->make($request->all(), [
            "name" => "required|string|max:255",
            "category" => "required|string|max:255",
            "description" => "required|string"
        ]);

        if($validator->fails()){
            return $this->BadRequest($validator);
        }

        return $this->Ok(null, "Proceed To Next Step!");
    }
    public function checkStepTwo(Request $request){
        $validator = validator()->make($request->all(), [
            "images" => 'required|mimes:jpeg,jpg,png',
        ]);

        if($validator->fails()){
            return $this->BadRequest($validator);
        }

        return $this->Ok(null, "Proceed To Next Step!");
    }

}
