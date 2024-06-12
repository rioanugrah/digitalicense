<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Orders;
use \Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Product $product,
        Orders $orders
    )
    {
        $this->product = $product;
        $this->orders = $orders;
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $live_date = Carbon::now();
        $data['product'] = $this->product;
        $data['order_day'] = $this->orders->where('user_generate',auth()->user()->generate)->where('created_at','like','%'.$live_date->format('Y-m-d').'%');
        return view('home',$data);
    }
}
