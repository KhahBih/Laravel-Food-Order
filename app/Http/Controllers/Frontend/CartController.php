<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function AddToCart($id){
        $products = Product::find($id);

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
           $cart[$id]['quantity']++;
        } else {
           $priceToShow = isset($products->discount_price) ? $products->discount_price : $products->price;
           $cart[$id] = [
            'id' => $id,
            'name' => $products->name,
            'image' => $products->image,
            'price' => $priceToShow,
            'client_id' => $products->client_id,
            'quantity' => 1
           ];
        }
        session()->put('cart',$cart);

        // return response()->json($cart);
        $notification = array(
            'message' => 'Add to Cart Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
        // return response()->json($cart);
    }

    public function updateCartQuantity(Request $request){
        $cart = session()->get('cart',[]);

        if (isset($cart[$request->id])) {
           $cart[$request->id]['quantity'] = $request->quantity;
           session()->put('cart',$cart);
        }
        $subTotal = 0;
        foreach($cart as $key => $item){
            $subTotal += (int)$item['price']*(int)$item['quantity'];
        }

         return $data = [
            'quantity' => $cart[$request->id]['quantity'],
            'id' => $request->id,
            'price' => $cart[$request->id]['price'],
            'subTotal' => $subTotal
         ];

    }

    public function UpdateCartSubtotal(Request $request){
        $cart = session()->get('cart',[]);
        $subTotal = 0;
        $count = 0;
        foreach($cart as $key => $item){
            $subTotal += (int)$item['price']*(int)$item['quantity'];
            $count++;
        }

         return $data = [
            'subTotal' => $subTotal,
            'count' => $count
         ];

    }

    public function CartRemove(Request $request){
        $cart = session()->get('cart',[]);

        if (isset($cart[$request->id])) {
           unset($cart[$request->id]);
           session()->put('cart',$cart);
        }
        $notification = array(
            'message' => 'Product Remove Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
     }
}
