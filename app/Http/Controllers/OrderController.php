<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\OrdersDetail;
use Validator;

class OrderController extends Controller
{
    function __construct(
        Orders $orders,
        OrdersDetail $orders_detail
    ){
        $this->orders = $orders;
        $this->orders_detail = $orders_detail;
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

    public function detail_input_license_simpan(Request $request,$order_code,$id)
    {
        $rules = [
            'product_license'  => 'required',
        ];

        $messages = [
            'product_license.required'  => 'Product Key wajib diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {
            $orderDetail = $this->orders_detail->where('orders_id',$id)->first();
            $orderDetail->product_license = $request->product_license;
            $orderDetail->update();

            if ($orderDetail) {
                return redirect()->route('orders.detail',['order_code' => $order_code, 'id' => $id])
                ->with('success',$orderDetail->order_name.' Product Key successfully');
            }
        }
    }
}
