<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Product;

class Controller extends BaseController
{
  /**
   * method to insert new URL, or update if exists
   */
  public function insert(Request $request){
    $input = $request->input("url");

    $meta = $this->fetchMeta($input);
    if(false!==strpos($meta["price"], ".") ){
      $meta["price"] = explode(".", $meta["price"])[0];
    }

    $prod = Product::updateOrCreate(
      [
        "url"=>$input
      ],
      [
        "description" => $meta["description"],
        "images" => $meta["image"],
        "title" => $meta["title"],
        "last_price" => $meta["price"]
      ]
    );

    $prod->prices()->create([
      "price" => $meta["price"],
      "currency" => $meta["currency"]
    ]);

    return response()->json($prod);
  }

  /**
   * method to list all tracked URLs
   */
  public function list(){
    $ret = Product::all();
    return response()->json($ret);
  }

  /**
   * method to display all prices for a particular product URL
   */
  public function detail(Request $request){
    $input = $request->input("url");
    $ret = Product::where('url','=', $input)->with("prices")->first();
    return response()->json($ret);
  }

  /**
   * method to fetch metadata from URL
   */
  protected function fetchMeta($url){
    #fetch the product's web content
    $res = file_get_contents($url);

    #extract the html head
    $res = substr($res, 0, strpos($res, "</head") );

    $res = array_filter(
      array_map(
        #cleanup each data
        function($v){return trim($v);},

        #split data by meta tag
        explode("<meta", $res )
      ),

      #pick only meta description & open graph properties
      function($v){return 0===strpos($v,'name="description"')||0===strpos($v,"property");}
    );

    $meta = [];
    foreach ($res as $v) {
      $key = false;#resets meta key

      if(false!==strpos($v,'name="description"') ){
        $key = "description";
      }else
      if(false!==strpos($v,'property="og:title"') ){
        $key = "title";
      }else
      if(false!==strpos($v,'property="og:image"') ){
        $key = "image";
      }else
      if(false!==strpos($v,'property="product:price:amount"') ){
        $key = "price";
      }else
      if(false!==strpos($v,'property="product:price:currency"') ){
        $key = "currency";
      }

      if($key){
        $s = substr($v, strpos($v, 'content="')+9);
        $meta[$key] = substr($s, 0, strpos($s, '"'));
      }
    }
    return $meta;
  }
}
