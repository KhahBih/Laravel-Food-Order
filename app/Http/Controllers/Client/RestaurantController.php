<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\Menu;
use App\Models\City;
use App\Models\Gallery;
use App\Traits\ImageUploadTrait;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class RestaurantController extends Controller
{
    use ImageUploadTrait;
    public function AllMenu(){
        $id = Auth::guard('client')->id();
        $menu = Menu::where('client_id', $id)->get();
        return view('client.backend.menu.all_menu', compact('menu'));
    }
    // End Method

    public function AddMenu(){

        return view('client.backend.menu.add_menu');
    }
    // End Method

    public function StoreMenu(Request $request){

        if ($request->file('image')) {
            $bannerPath = $this->updateImage($request, 'image', 'upload/menu');

            Menu::create([
                'client_id' => $request->id,
                'menu_name' => $request->menu_name,
                'image' => $bannerPath,
            ]);
        }

        $notification = array(
            'message' => 'Menu Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.menu')->with($notification);

    }
    // End Method

    public function EditMenu($id){
        $menu = Menu::find($id);
        return view('client.backend.menu.edit_menu', compact('menu'));
    }
     // End Method

     public function UpdateMenu(Request $request){

        $menu_id = $request->id;

        if ($request->file('image')) {
            $bannerPath = $this->updateImage($request, 'image', 'upload/menu');

            Menu::find($menu_id)->update([
                'client_id' => $request->client_id,
                'menu_name' => $request->menu_name,
                'image' => $bannerPath,
            ]);
            $notification = array(
                'message' => 'Menu Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.menu')->with($notification);

        } else {

            Menu::find($menu_id)->update([
                'menu_name' => $request->menu_name,
            ]);
            $notification = array(
                'message' => 'Menu Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.menu')->with($notification);

        }

    }
    // End Method
    public function DeleteMenu($id){
        $item = Menu::find($id);
        $img = $item->image;
        unlink($img);

        Menu::find($id)->delete();

        $notification = array(
            'message' => 'Menu Delete Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
    // End Method

    public function AllProduct(){
        $id = Auth::guard('client')->id();
        $product = Product::where('client_id', $id)->get();
        return view('client.backend.product.all_product', compact('product'));
    }

    public function AddProduct(){
        $id = Auth::guard('client')->id();
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::where('client_id', $id)->get();
        return view('client.backend.product.add_product', compact('category','city','menu'));
    }

    public function StoreProduct(Request $request){
        $pcode = IdGenerator::generate(['table' => 'products','field' => 'code', 'length' => 5, 'prefix' => 'PC']);

        if ($request->file('image')) {
            $bannerPath = $this->updateImage($request, 'image', 'upload/menu');

            Product::create([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ','-',$request->name)),
                'category_id' => $request->category_id,
                'city_id' => $request->city_id,
                'menu_id' => $request->menu_id,
                'code' => $pcode,
                'qty' => $request->qty,
                'size' => $request->size,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'client_id' => Auth::guard('client')->id(),
                'most_populer' => $request->most_populer,
                'best_seller' => $request->best_seller,
                'status' => 1,
                'created_at' => Carbon::now(),
                'image' => $bannerPath,
            ]);
        }

        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification);
    }

    public function EditProduct($id){
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();
        $product = Product::find($id);
        return view('client.backend.product.edit_product', compact('category','city','menu','product'));
    }

    public function UpdateProduct(Request $request){
        $pro_id = $request->id;

        if ($request->file('image')) {
            $bannerPath = $this->updateImage($request, 'image', 'upload/menu');

            Product::find($pro_id)->update([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ','-',$request->name)),
                'category_id' => $request->category_id,
                'city_id' => $request->city_id,
                'menu_id' => $request->menu_id,
                'qty' => $request->qty,
                'size' => $request->size,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'most_populer' => $request->most_populer,
                'best_seller' => $request->best_seller,
                'created_at' => Carbon::now(),
                'image' => $bannerPath,
            ]);

            $notification = array(
                'message' => 'Product Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.product')->with($notification);

        }else{

            Product::find($pro_id)->update([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ','-',$request->name)),
                'category_id' => $request->category_id,
                'city_id' => $request->city_id,
                'menu_id' => $request->menu_id,
                'qty' => $request->qty,
                'size' => $request->size,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'most_populer' => $request->most_populer,
                'best_seller' => $request->best_seller,
                'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Product Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.product')->with($notification);

        }

    }
    // End Method

    public function DeleteProduct($id){
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

    public function ChangeStatus(Request $request){
        $product = Product::find($request->product_id);
        $product->status = $request->status;
        $product->save();
        return response()->json(['success' => 'Status Change Successfully']);
    }
     // End Method

     /////////// All Gallery Method Start

     public function AllGallery(){
        $id = Auth::guard('client')->id();
        $gallery = Gallery::where('client_id', $id)->get();
        return view('client.backend.gallery.all_gallery', compact('gallery'));
    }

    public function AddGallery(){
        return view('client.backend.gallery.add_gallery' );
    }
    // End Method

    public function StoreGallery(Request $request){

        $images = $request->file('gallery_img');
        $bannerPath = $this->uploadMultiImage($request, 'gallery_img', 'upload/gallery');

        foreach ($bannerPath as $path) {

            Gallery::insert([
                'client_id' => Auth::guard('client')->id(),
                'gallery_img' => $path,
            ]);
        } // end foreach

        $notification = array(
            'message' => 'Gallery Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.gallery')->with($notification);

    }

    public function EditGallery($id){
        $gallery = Gallery::find($id);
        return view('client.backend.gallery.edit_gallery',compact('gallery'));
     }
     // End Method

     public function UpdateGallery(Request $request){

        $id = $request->id;
        $bannerPath = $this->uploadImage($request, 'gallery_img', 'upload/gallery');

        if ($request->hasFile('gallery_img')) {
            $gallery = Gallery::find($id);
            if ($gallery->gallery_img) {
                $img = $gallery->gallery_img;
                unlink($img);
            }

            $gallery->update([
                'gallery_img' => $bannerPath,
            ]);

            $notification = array(
                'message' => 'Menu Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.gallery')->with($notification);

        } else {

            $notification = array(
                'message' => 'No Image Selected for Update',
                'alert-type' => 'warning'
            );

            return redirect()->back()->with($notification);
        }
    }
    // End Method

    public function DeleteGallery($id){
        $item = Gallery::find($id);
        $img = $item->gallery_img;
        unlink($img);

        Gallery::find($id)->delete();

        $notification = array(
            'message' => 'Gallery Delete Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
