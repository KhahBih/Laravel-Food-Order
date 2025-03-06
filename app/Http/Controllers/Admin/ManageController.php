<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageUploadTrait;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Client;
use App\Models\Product;
use App\Models\City;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;
use App\Models\Gllery;

class ManageController extends Controller
{
    use ImageUploadTrait;
    public function AdminAllProduct(){
        $product = Product::orderBy('id','desc')->get();
        return view('admin.backend.product.all_product', compact('product'));
    }
    // End Method

    public function AdminAddProduct(){
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();
        $client = Client::latest()->get();
        return view('admin.backend.product.add_product', compact('category','city','menu','client'));
    }
    // End Method
    public function AdminStoreProduct(Request $request){

        $pcode = IdGenerator::generate(['table' => 'products','field' => 'code', 'length' => 5, 'prefix' => 'PC']);

        if ($request->file('image')) {
            $path = $this->uploadImage($request, 'image', 'upload/product');
            $product = new Product();

            $product->name = $request->name;
            $product->slug = strtolower(str_replace(' ','-',$request->name));
            $product->category_id = $request->category_id;
            $product->city_id = $request->city_id;
            $product->menu_id = $request->menu_id;
            $product->code = $pcode;
            $product->qty = $request->qty;
            $product->size = $request->size;
            $product->price = $request->price;
            $product->discount_price = $request->discount_price;
            $product->client_id = $request->client_id;
            $product->most_populer = $request->most_populer;
            $product->best_seller = $request->best_seller;
            $product->status = 1;
            $product->created_at = Carbon::now();
            $product->image = $path;
            $product->save();
        }

        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.all.product')->with($notification);

    }
    // End Method

    public function AdminEditProduct($id){
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();
        $client = Client::latest()->get();
        $product = Product::find($id);
        return view('admin.backend.product.edit_product', compact('category','city','menu','product','client'));
    }

}
