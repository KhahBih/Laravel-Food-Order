<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Menu;
use App\Traits\ImageUploadTrait;
use App\Models\Product;


class RestaurantController extends Controller
{
    use ImageUploadTrait;
    public function AllMenu(){
        $menu = Menu::latest()->get();
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
        $product = Product::latest()->get();
        return view('client.backend.product.all_product', compact('product'));
    }

    public function AddProduct(){
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();
        return view('client.backend.product.add_product', compact('category','city','menu'));
    }
}
