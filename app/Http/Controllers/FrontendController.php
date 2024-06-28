<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Payment\TripayController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Orders;
use App\Models\OrdersDetail;
use \Carbon\Carbon;

use App\Mail\NoReplyEmail;

use DB;
class FrontendController extends Controller
{
    function __construct(
        TripayController $tripay_payment,
        Product $product,
        ProductDetail $product_detail,
        Category $category,
        CategoryDetail $category_detail,
        Orders $orders,
        OrdersDetail $orders_detail
    ){
        $this->tripay_payment = $tripay_payment;
        if (env('TRIPAY_IS_PRODUCTION') == false) {
            $this->tripay_api_key = env('TRIPAY_API_KEY_SANDBOX');
            $this->tripay_private_key = env('TRIPAY_PRIVATE_KEY_SANDBOX');
            $this->tripay_merchant = env('TRIPAY_MERCHANT_SANDBOX');
            $this->tripay_url = env('TRIPAY_SANDBOX');
        }else{
            $this->tripay_api_key = env('TRIPAY_API_KEY_PRODUCTION');
            $this->tripay_private_key = env('TRIPAY_PRIVATE_KEY_PRODUCTION');
            $this->tripay_merchant = env('TRIPAY_MERCHANT_PRODUCTION');
            $this->tripay_url = env('TRIPAY_PRODUCTION');
        }
        $this->product = $product;
        $this->product_detail = $product_detail;
        $this->category = $category;
        $this->category_detail = $category_detail;
        $this->orders = $orders;
        $this->orders_detail = $orders_detail;
    }

    public function index()
    {
        $data['categories'] = $this->category;
        return view('frontend.index',$data);
    }

    public function product($category)
    {
        $data['categories'] = $this->category->where('slug',$category)->first();
        return view('frontend.product.index',$data);
    }

    public function product_category($category,$category_id)
    {
        // dd($category_id);
        $data['categories'] = $this->category->where('slug',$category)
                                            ->first();
        $data['products'] = $this->product->where('category_id',$data['categories']->id)
                                        ->where('category_detail_id',$category_id)
                                        ->get();
        // $data['category_detail_list'] = $this->category_detail->where('category_id',$data['categories']->id)
        //                                                     // ->where('category_detail_id',$category_id)
        //                                                     ->get();
                                            // dd($data);
        return view('frontend.product.category_product',$data);
    }

    public function product_detail($category,$category_id,$slug)
    {
        $data['categories'] = $this->category->select([
                                                'product.id as id',
                                                'category.slug as slug_category',
                                                'product.slug as slug_product',
                                                'product.category_id as category_id',
                                                'product.category_detail_id as category_detail_id',
                                                'product.name as name',
                                                'product.description as description',
                                                'product.price as price',
                                                'product.qty as qty',
                                                'product.image as image',
                                                'product.created_at as created_at',
                                                'product.updated_at as updated_at',
                                            ])
                                            ->leftJoin('product','product.category_id','category.id')
                                            ->where('category.slug',$category)
                                            ->where('product.slug',$slug)
                                            ->first();
        if (empty($data['categories'])) {
            return redirect()->back();
        }
        return view('frontend.product.detail',$data);
    }

    public function product_checkout($category,$category_id,$slug)
    {
        $data['categories'] = $this->category->select([
                                        'product.id as id',
                                        'category.slug as slug_category',
                                        'product.slug as slug_product',
                                        'product.category_id as category_id',
                                        'product.category_detail_id as category_detail_id',
                                        'product.name as name',
                                        'product.description as description',
                                        'product.price as price',
                                        'product.qty as qty',
                                        'product.image as image',
                                        'product.created_at as created_at',
                                        'product.updated_at as updated_at',
                                    ])
                                    ->leftJoin('product','product.category_id','category.id')
                                    ->where('category.slug',$category)
                                    ->where('product.slug',$slug)
                                    ->first();
        if (empty($data['categories'])) {
            return redirect()->back();
        }
        $data['channels'] = json_decode($this->tripay_payment->getPayment())->data;
        return view('frontend.product.checkout',$data);
    }

    public function product_checkout_buy(Request $request,$category,$category_id,$slug)
    {
        DB::beginTransaction();
        $product = $this->product->where('slug',$slug)->first();
        if ($product->qty == 0) {
            return redirect()->back()->with(['error' => 'Stock '.$product->name.' Telah habis']);
        }
        $kode_jenis_transaksi = 'TRX';
        $kode_random_transaksi = Carbon::now()->format('Ym').rand(100,999);
        $input['id'] = Str::uuid()->toString();
        $input['order_code'] = $kode_jenis_transaksi.'-'.$kode_random_transaksi;

        $tripay = $this->tripay_payment;
        $paymentDetail = $tripay->requestTransaction(
            $product->name,
            $request->method,$product->price,
            $request->billing_name,$request->billing_email,$request->billing_phone,
            $input['order_code'],route('transactions.detail',['order_code' => $input['order_code']])
        );

        $input['order_reference'] = json_decode($paymentDetail)->data->reference;
        $input['billing_order'] = json_encode([
            'name' => $request->billing_name,
            'email' => $request->billing_email,
            'address' => $request->billing_address,
            'phone' => $request->billing_phone,
            'country' => $request->billing_country,
            'order_note' => $request->billing_order_note
        ]);
        $channels = json_decode($this->tripay_payment->getPayment())->data;
        foreach ($channels as $channel) {
            if ($request->method == $channel->code) {
                $price_layanan = $channel->total_fee->flat;
            }
        }
        $input['price'] = $product->price + $price_layanan;
        $input['status'] = 'Unpaid';
        $input['user_generate'] = auth()->user()->generate;
        // dd($input2);

        $order = $this->orders->create($input);
        DB::commit();

        if ($order) {
            $product->qty = $product->qty - 1;
            $product->update();

            // $input2['id'] = Str::uuid()->toString();
            // $input2['orders_id'] = $input['id'];
            // $input2['order_name'] = $product->name;
            // $input2['product_id'] =$product->id;
            // $input2['qty'] = 1;
            // $input2['price'] = $input['price'];

            // $this->orders_detail->create($input2);

            $input2 = array(
                [
                    'id' => Str::uuid()->toString(),
                    'orders_id' => $input['id'],
                    'order_name' => $product->name,
                    'qty' => 1,
                    'price' => $product->price
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'orders_id' => $input['id'],
                    'order_name' => 'Biaya Layanan',
                    'qty' => 1,
                    'price' => $price_layanan
                ],
            );

            foreach ($input2 as $input_2) {
                $this->orders_detail->create($input_2);
            }
            $comment = '<p>Silahkan Anda melakukan pembayaran di website kami</p>';
            Mail::to($request->billing_email)
                ->cc('marketing@digitalicense.biz.id')
                // ->subject('Konfirmasi Pembayaran')
                ->send(new NoReplyEmail('Konfirmasi Pembayaran',$input['order_code'],$request->billing_name,$request->billing_email,$input['price'],$input['status'],$comment));
            // $user = \App\Models\User::where('id',1)
            //                         // ->orWhere('id',auth()->user()->id)
            //                         ->get();
            // $notif = [
            //     'id' => $input['id'],
            //     // 'url' => json_decode($paymentDetail)->data->checkout_url,
            //     'url' => route('transactions.detail',['order_code' => $input['order_code']]),
            //     'title' => 'Pesanan Baru',
            //     'message' => 'Kode Order '.$input['order_code'].' - Sedang Melakukan Pembayaran',
            //     'color_icon' => 'warning',
            //     'icon' => 'bx bx-cube',
            //     'publish' => Carbon::now(),
            // ];
            // Notification::send($user,new NotificationNotif($notif));

            return redirect(json_decode($paymentDetail)->data->checkout_url);
            // return response()->json([
            //     'success' => true,
            //     'message_title' => 'Success',
            //     'message_content' => 'The purchase has been successful, please wait',
            //     'link' => json_decode($paymentDetail)->data->checkout_url
            // ]);
        }
    }

    // public function product_detail($category,$slug)
    // {
    //     $data['categories'] = $this->category
    //                                         ->select([
    //                                           'product.id as id',
    //                                           'category.slug as slug_category',
    //                                           'product.slug as slug_product',
    //                                           'product.name as name',
    //                                           'product.description as description',
    //                                           'product.price as price',
    //                                           'product.qty as qty',
    //                                           'product.image as image',
    //                                           'product.created_at as created_at',
    //                                           'product.updated_at as updated_at',
    //                                         ])
    //                                         ->leftJoin('product','product.category_id','category.id')
    //                                         ->where('category.slug',$category)
    //                                         ->where('product.slug',$slug)
    //                                         ->first();

    //     if (empty($data['categories'])) {
    //         return redirect()->back();
    //     }
    //     return view('frontend.product.detail',$data);
    // }

    // public function product_checkout($category,$slug)
    // {
    //     $data['categories'] = $this->category
    //                                         ->select([
    //                                           'product.id as id',
    //                                           'category.slug as slug_category',
    //                                           'product.slug as slug_product',
    //                                           'product.name as name',
    //                                           'product.description as description',
    //                                           'product.price as price',
    //                                           'product.qty as qty',
    //                                           'product.image as image',
    //                                           'product.created_at as created_at',
    //                                           'product.updated_at as updated_at',
    //                                         ])
    //                                         ->leftJoin('product','product.category_id','category.id')
    //                                         ->where('category.slug',$category)
    //                                         ->where('product.slug',$slug)
    //                                         ->first();
    //     if (empty($data['categories'])) {
    //         return redirect()->back();
    //     }
    //     $data['channels'] = json_decode($this->tripay_payment->getPayment())->data;
    //     // dd($data);
    //     return view('frontend.product.checkout',$data);
    // }

    // public function product_checkout_buy(Request $request,$category,$slug)
    // {
    //     DB::beginTransaction();
    //     $product = $this->product->where('slug',$slug)->first();
    //     if ($product->qty == 0) {
    //         return redirect()->back()->with(['error' => 'Stock '.$product->name.' Telah habis']);
    //     }
    //     $kode_jenis_transaksi = 'TRX';
    //     $kode_random_transaksi = Carbon::now()->format('Ym').rand(100,999);
    //     $input['id'] = Str::uuid()->toString();
    //     $input['order_code'] = $kode_jenis_transaksi.'-'.$kode_random_transaksi;

    //     $tripay = $this->tripay_payment;
    //     $paymentDetail = $tripay->requestTransaction(
    //         $product->name,
    //         $request->method,$product->price,
    //         $request->billing_name,$request->billing_email,$request->billing_phone,
    //         $input['order_code'],route('transactions.detail',['order_code' => $input['order_code']])
    //     );

    //     $input['order_reference'] = json_decode($paymentDetail)->data->reference;
    //     $input['billing_order'] = json_encode([
    //         'name' => $request->billing_name,
    //         'email' => $request->billing_email,
    //         'address' => $request->billing_address,
    //         'phone' => $request->billing_phone,
    //         'country' => $request->billing_country,
    //         'order_note' => $request->billing_order_note
    //     ]);
    //     $channels = json_decode($this->tripay_payment->getPayment())->data;
    //     foreach ($channels as $channel) {
    //         if ($request->method == $channel->code) {
    //             $price_layanan = $channel->total_fee->flat;
    //         }
    //     }
    //     $input['price'] = $product->price + $price_layanan;
    //     $input['status'] = 'Unpaid';
    //     $input['user_generate'] = auth()->user()->generate;
    //     // dd($input2);

    //     $order = $this->orders->create($input);
    //     DB::commit();

    //     if ($order) {
    //         $product->qty = $product->qty - 1;
    //         $product->update();

    //         // $input2['id'] = Str::uuid()->toString();
    //         // $input2['orders_id'] = $input['id'];
    //         // $input2['order_name'] = $product->name;
    //         // $input2['product_id'] =$product->id;
    //         // $input2['qty'] = 1;
    //         // $input2['price'] = $input['price'];

    //         // $this->orders_detail->create($input2);

    //         $input2 = array(
    //             [
    //                 'id' => Str::uuid()->toString(),
    //                 'orders_id' => $input['id'],
    //                 'order_name' => $product->name,
    //                 'qty' => 1,
    //                 'price' => $product->price
    //             ],
    //             [
    //                 'id' => Str::uuid()->toString(),
    //                 'orders_id' => $input['id'],
    //                 'order_name' => 'Biaya Layanan',
    //                 'qty' => 1,
    //                 'price' => $price_layanan
    //             ],
    //         );

    //         foreach ($input2 as $input_2) {
    //             $this->orders_detail->create($input_2);
    //         }
    //         $comment = '<p>Silahkan Anda melakukan pembayaran di website kami</p>';
    //         Mail::to($request->billing_email)
    //             ->cc('marketing@digitalicense.biz.id')
    //             // ->subject('Konfirmasi Pembayaran')
    //             ->send(new NoReplyEmail('Konfirmasi Pembayaran',$input['order_code'],$request->billing_name,$request->billing_email,$input['price'],$input['status'],$comment));
    //         // $user = \App\Models\User::where('id',1)
    //         //                         // ->orWhere('id',auth()->user()->id)
    //         //                         ->get();
    //         // $notif = [
    //         //     'id' => $input['id'],
    //         //     // 'url' => json_decode($paymentDetail)->data->checkout_url,
    //         //     'url' => route('transactions.detail',['order_code' => $input['order_code']]),
    //         //     'title' => 'Pesanan Baru',
    //         //     'message' => 'Kode Order '.$input['order_code'].' - Sedang Melakukan Pembayaran',
    //         //     'color_icon' => 'warning',
    //         //     'icon' => 'bx bx-cube',
    //         //     'publish' => Carbon::now(),
    //         // ];
    //         // Notification::send($user,new NotificationNotif($notif));

    //         return redirect(json_decode($paymentDetail)->data->checkout_url);
    //         // return response()->json([
    //         //     'success' => true,
    //         //     'message_title' => 'Success',
    //         //     'message_content' => 'The purchase has been successful, please wait',
    //         //     'link' => json_decode($paymentDetail)->data->checkout_url
    //         // ]);
    //     }
    // }
}
