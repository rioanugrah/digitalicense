<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Orders;

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
        $data['product'] = $this->product;
        $data['orders'] = $this->orders->where('user_generate',auth()->user()->generate);
        return view('home',$data);
    }
}
