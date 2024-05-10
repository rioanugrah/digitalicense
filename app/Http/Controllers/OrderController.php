<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;

class OrderController extends Controller
{
    function __construct(
        Orders $orders
    ){
        $this->orders = $orders;
    }

    public function index()
    {
        $cek_user = \DB::table('model_has_roles')->select('role_id')->where('model_id',auth()->user()->id)->first();
        if ($cek_user->role_id == 1) {
            $data['orders'] = $this->orders->orderBy('created_at','desc')->get();
            return view('orders.index',$data);
        }else{
            $data['orders'] = $this->orders->where('user_generate',auth()->user()->generate)->orderBy('created_at','desc')->get();
            return view('orders.index',$data);
        }
    }

    public function detail($order_code,$id)
    {
        $data['order'] = $this->orders->where('order_code',$order_code)->where('id',$id)->first();
        if (empty($data['order'])) {
            return redirect()->back()->with(['error','Data tidak ditemukan']);
        }
        $data['cek_user'] = \DB::table('model_has_roles')->select('role_id')->where('model_id',auth()->user()->id)->first();

        return view('orders.detail',$data);
    }

    public function detail_input_license($order_code,$id)
    {

    }
}
