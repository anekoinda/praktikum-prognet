<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $this->validate($request,[
            'bukti'=>'required',
        ]);

        $file = $request->file('bukti');
        
		$tujuan_upload = 'storage/1/bukti';
        $image_name = time().'.'.$file->extension();
		$file->move($tujuan_upload,$image_name);
        $status=$order->fill($data)->save();
        if($status){
            request()->session()->flash('success','Payment successfully updated');
        }
        else{
            request()->session()->flash('error','Error occurred, Please try again!');
        }
        return view('frontend.pages.payment');
    }

    public function orderDetail(Request $request, $id)
    {
        $order=Order::findOrFail($id);
        $this->validate($request,[
            'bukti'=>'required',
        ]);

        $file = $request->file('bukti');
        
		$tujuan_upload = 'storage/1/bukti';
        $image_name = time().'.'.$file->extension();
		$file->move($tujuan_upload,$image_name);
        $status=$order->fill($data)->save();
        if($status){
            request()->session()->flash('success','Payment successfully updated');
        }
        else{
            request()->session()->flash('error','Error occurred, Please try again!');
        }
        return view('frontend.pages.payment');
    }
}
