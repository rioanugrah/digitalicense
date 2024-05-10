<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Payment\TripayController;
use App\Models\Orders;
use \Carbon\Carbon;
class TransactionController extends Controller
{
    function __construct(
        TripayController $tripay_payment,
        Orders $orders
    ){
        $this->middleware('permission:transaction-list', ['only' => ['index']]);
        $this->tripay_payment = $tripay_payment;
        $this->orders = $orders;
    }

    public function index()
    {
        $cek_user = \DB::table('model_has_roles')->select('role_id')->where('model_id',auth()->user()->id)->first();
        if ($cek_user->role_id == 1) {
            $data['orders'] = $this->orders->orderBy('created_at','desc')->get();
            return view('transactions.index',$data);
        }else{
            $data['orders'] = $this->orders->where('user_generate',auth()->user()->generate)->orderBy('created_at','desc')->get();
            return view('transactions.index',$data);
        }
    }

    public function detail($order_code)
    {
        $data['order'] = $this->orders->where('order_code',$order_code)->first();
        if (empty($data['order'])) {
            return redirect()->back()->with(['error','Data tidak ditemukan']);
        }
        return view('transactions.detail',$data);
    }

    public function check_transaction($order_code,$order_reference)
    {
        $paymentDetail = $this->tripay_payment->detailTransaction($order_reference);
        return redirect(json_decode($paymentDetail)->data->checkout_url);
    }
}
