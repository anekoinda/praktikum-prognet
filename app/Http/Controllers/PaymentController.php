<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\User;
use PDF;
use Notification;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;

class PaymentController extends Controller
{
    public function index()
    {
        $orders=Order::orderBy('id','DESC');
        return view('frontend.pages.payment')->with('orders',$orders);
    }
    public function store()
    {
        $orders=Order::orderBy('id','DESC');
        return view('frontend.pages.payment')->with('orders',$orders);
    }

    public function cancelOrder(Request $request, $id)
    {
        $order=Order::find($request->id);
        $cart = Cart::all();
        if($order->bukti == NULL){
            $order->status = 'cancel';
            $cart->status = 'cancel';
        }
        else{
            request()->session()->flash('gabisa euy');
        }
        $order->save();
        return view('frontend.pages.payment')->with('orders',$order);
        
    }

    public function orderUpdate(Request $request, $id)
    {
        $order=Order::findOrFail($id);

        $request->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $imageName = time().'.'.$request->bukti->extension();  
     
        $request->bukti->move(public_path('images'), $imageName);
  
        /* Store $imageName name in DATABASE from HERE */
        $order->update(['bukti' => $imageName]);
    
        return redirect('/cart/order')
            ->with('success','You have successfully upload image.')
            ->with('image',$imageName); 
    }

    public function orderDetail(Request $request, $id)
    {
        $data = Cart::with('product')->where('user_id', Auth::user()->id)->where('order_id', $id)->get();
        
        return view('frontend.pages.order-detail')->with('carts', $data);
    }
       
}
