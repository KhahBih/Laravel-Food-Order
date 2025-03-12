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
use App\Models\Banner;
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

    public function AdminUpdateProduct(Request $request){
        $pro_id = $request->id;
        $product = Product::findOrFail($pro_id);

        if ($request->file('image')) {
            $path = $this->updateImage($request, 'image', 'upload/product', $product->image);

            $product->name = $request->name;
            $product->slug = strtolower(str_replace(' ','-',$request->name));
            $product->category_id = $request->category_id;
            $product->city_id = $request->city_id;
            $product->menu_id = $request->menu_id;
            $product->qty = $request->qty;
            $product->size = $request->size;
            $product->price = $request->price;
            $product->discount_price = $request->discount_price;
            $product->most_populer = $request->most_populer;
            $product->best_seller = $request->best_seller;
            $product->status = 1;
            $product->created_at = Carbon::now();
            $product->image = $path;
            $product->save();

            $notification = array(
                'message' => 'Product Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('admin.all.product')->with($notification);

        }else{
            $product->name = $request->name;
            $product->slug = strtolower(str_replace(' ','-',$request->name));
            $product->category_id = $request->category_id;
            $product->city_id = $request->city_id;
            $product->menu_id = $request->menu_id;
            $product->qty = $request->qty;
            $product->size = $request->size;
            $product->price = $request->price;
            $product->discount_price = $request->discount_price;
            $product->most_populer = $request->most_populer;
            $product->best_seller = $request->best_seller;
            $product->status = 1;
            $product->created_at = Carbon::now();
            $product->save();

            $notification = array(
                'message' => 'Product Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('admin.all.product')->with($notification);

        }

    }
    // End Method

    public function AdminDeleteProduct($id){
        $item = Product::find($id);
        $img = $item->image;
        unlink($img);

        Product::find($id)->delete();

        $notification = array(
            'message' => 'Product Delete Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }

    public function PendingRestaurant(){
        $client = Client::where('status',0)->get();
        return view('admin.backend.restaurant.pending_restaurant',compact('client'));
    }

    public function ClientChangeStatus(Request $request){
        $client = Client::find($request->client_id);
        $client->status = $request->status;
        $client->save();
        return response()->json(['success' => 'Status Change Successfully']);
    }
     // End Method

     public function ApproveRestaurant(){
        $client = Client::where('status',1)->get();
        return view('admin.backend.restaurant.approve_restaurant',compact('client'));
    }

    public function AllBanner(){
        $banner = Banner::latest()->get();
        return view('admin.backend.banner.all_banner',compact('banner'));
      }

      public function BannerStore(Request $request){

        if ($request->file('image')) {
            $path = $this->uploadImage($request, 'image', 'upload/banner');

            Banner::create([
                'url' => $request->url,
                'image' => $path,
            ]);
        }

        $notification = array(
            'message' => 'Banner Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }

    public function EditBanner($id){
        $banner = Banner::find($id);
        if ($banner) {
            $banner->image = asset($banner->image);
        }
        return response()->json($banner);
    }

    public function BannerUpdate(Request $request){
        $id = $request->banner_id;
        if ($request->file('image')) {
            $banner = Banner::find($id);

            $path = $this->updateImage($request, 'image', 'upload/banner', $banner->image);
            $banner->url = $request->url;
            $banner->image = $path;
            $banner->save();

            $notification = array(
                'message' => 'Banner Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);

        } else {

            Banner::find($id)->update([
                'url' => $request->url,
            ]);
            $notification = array(
                'message' => 'Banner Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);

        }

    }
    // End Method

    public function DeleteBanner($id){
        $item = Banner::find($id);
        $img = $item->image;
        unlink($img);

        Banner::find($id)->delete();

        $notification = array(
            'message' => 'Banner Delete Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
