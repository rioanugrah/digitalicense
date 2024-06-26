<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Payment\TripayController;
use App\Notifications\NotificationNotif;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Category;
use App\Models\Orders;
use App\Models\OrdersDetail;
use App\Models\OrderLicense;
use App\Mail\NoReplyEmail;

use \Carbon\Carbon;
use Validator;
use File;
use DB;
use Notification;

class ProductController extends Controller
{
    function __construct(
        TripayController $tripay_payment,
        Product $product,
        ProductDetail $product_detail,
        Category $category,
        Orders $orders,
        OrdersDetail $orders_detail,
        OrderLicense $orders_license
    ){
        $this->middleware('permission:product-list', ['only' => ['index']]);
        $this->middleware('permission:product-create', ['only' => ['create','simpan']]);
        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['hapus']]);

        $this->tripay_payment = $tripay_payment;
        $this->product = $product;
        $this->product_detail = $product_detail;
        $this->category = $category;
        $this->orders = $orders;
        $this->orders_detail = $orders_detail;
        $this->orders_license = $orders_license;
    }

    public function index()
    {
        // Cek User
        $cek_user = \DB::table('model_has_roles')->select('role_id')->where('model_id',auth()->user()->id)->first();
        // dd($cek_user);
        if ($cek_user->role_id == 1) {
            $data['products'] = $this->product->orderBy('created_at','desc')->get();
            return view('products.index',$data);
        }else{
            $data['products'] = $this->product->orderBy('created_at','desc')->paginate(10);
            $data['categories'] = $this->category->all();
            return view('products.user.index',$data);
        }
    }

    public function create()
    {
        $data['categories'] = $this->category->all();
        return view('products.create',$data);
    }

    public function search(Request $request)
    {
        $data['products'] = $this->product->where('name','like','%'.$request->search.'%')->orderBy('created_at','desc')->get();
        return view('products.user.index',$data);
    }

    public function category_detail($category_id)
    {
        $category = $this->category->with('category_detail_list')->whereHas('category_detail_list', function($cd) use($category_id){
                                        $cd->where('category_id',$category_id);
                                    })->first();

        if (empty($category->category_detail_list)) {
            return response()->json([
                'success' => false,
                'message_title' => 'Gagal',
                'message_content' => 'Data Tidak Ditemukan'
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
                                    // dd($category);
    }

    public function simpan(Request $request)
    {
        $rules = [
            'category_id'  => 'required',
            'category_detail_id'  => 'required',
            'name'  => 'required',
            'price'  => 'required',
            'qty'  => 'required',
            'image'  => 'required',
            'keywords'  => 'required',
        ];

        $messages = [
            'name.required'  => 'Nama Produk wajib diisi.',
            'category_id.required'  => 'Kategori wajib diisi.',
            'category_detail_id.required'  => 'Kategori Detail wajib diisi.',
            'price.required'  => 'Harga wajib diisi.',
            'qty.required'  => 'Quantity wajib diisi.',
            'image.required'  => 'Image wajib diisi.',
            'keywords.required'  => 'Keywords wajib diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {
            $input['id'] = Str::uuid()->toString();
            $input['slug'] = Str::slug($request->name);
            $input['name'] = $request->name;
            $input['category_id'] = $request->category_id;
            $input['category_detail_id'] = $request->category_detail_id;
            $input['description'] = $request->description;
            $input['qty'] = $request->qty;
            $input['price'] = $request->price;
            // $input['image'] = $request->image;
            $image = $request->file('image');
            $img = \Image::make($image->path());
            $img = $img->encode('webp', 75);
            $input['image'] = time().'.webp';
            $img->save(public_path('product_digital/').$input['image']);

            if ($request->jenis_upload == 'url') {
                $input['link_file'] = $request->link_file;
            }elseif($request->jenis_upload == 'file'){
                if ($request->file('link_file')) {
                    $files = $request->file('link_file');
                    $namefile = $files->getClientOriginalName();
                    $files->move('product_digital/berkas/',$namefile);
                    $input['link_file'] = asset('product_digital/berkas/'.$namefile);
                }
            }
            // if ($request->file('link_file')) {
            //     $files = $request->file('link_file');
            //     $namefile = $files->getClientOriginalName();
            //     $files->move('product_digital/berkas/',$namefile);
            //     $input['link_file'] = $namefile;
            // }
            $input['keywords'] = $request->keywords;

            $product = $this->product->create($input);

            // for ($i=0; $i <= $request->qty ; $i++) {
            //     $no_product_detail = $this->product_detail->max('id');
            //     $this->product_detail->create([
            //         'id' => $no_product_detail+1,
            //         'product_id' => $input['id'],
            //         'status' => 'Open'
            //     ]);
            // }

            if($product){
                return redirect()->route('products')
                ->with('success','Product '.$input['name'].' created successfully');
            }

        }
        return redirect()->route('products.create')
            ->with(['error' => $validator->errors()->all()]);
    }

    public function detail($slug,$id)
    {
        $cek_user = \DB::table('model_has_roles')->select('role_id')->where('model_id',auth()->user()->id)->first();
        if ($cek_user->role_id == 1) {
            $data['product'] = $this->product->where('id',$id)->where('slug',$slug)->first();
            if (empty($data['product'])) {
                return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
            }
            return view('products.detail',$data);
        }else{
            $data['product'] = $this->product->where('id',$id)->where('slug',$slug)->first();
            if (empty($data['product'])) {
                return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
            }
            return view('products.user.detail',$data);
        }
    }

    public function edit($slug,$id)
    {
        $data['product'] = $this->product->where('id',$id)->where('slug',$slug)->first();
        if (empty($data['product'])) {
            return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
        }
        $data['categories'] = $this->category->all();

        return view('products.edit',$data);
    }

    public function update(Request $request, $slug, $id)
    {
        $rules = [
            'category_id'  => 'required',
            'category_detail_id'  => 'required',
            'name'  => 'required',
            'price'  => 'required',
            'qty'  => 'required',
            // 'image'  => 'required',
            'keywords'  => 'required',
        ];

        $messages = [
            'name.required'  => 'Nama Produk wajib diisi.',
            'category_id.required'  => 'Kategori wajib diisi.',
            'category_detail_id.required'  => 'Kategori Detail wajib diisi.',
            'price.required'  => 'Harga wajib diisi.',
            'qty.required'  => 'Quantity wajib diisi.',
            // 'image.required'  => 'Image wajib diisi.',
            'keywords.required'  => 'Keywords wajib diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {
            $product = $this->product->where('slug',$slug)->where('id',$id)->first();

            $input['slug'] = Str::slug($request->name);
            $input['name'] = $request->name;
            $input['category_id'] = $request->category_id;
            $input['category_detail_id'] = $request->category_detail_id;
            $input['description'] = $request->description;
            $input['qty'] = $request->qty;
            $input['price'] = $request->price;
            // $input['image'] = $request->image;
            if ($request->file('image')) {
                $image = $request->file('image');
                $img = \Image::make($image->path());
                $img = $img->encode('webp', 75);
                $input['image'] = time().'.webp';
                $img->save(public_path('product_digital/').$input['image']);

                $image_path = public_path('product_digital/'.$product->image);
                File::delete($image_path);
                $input['image'] = $input['image'];
            }

            if ($request->jenis_upload == 'url') {
                $input['link_file'] = $request->link_file;
            }elseif($request->jenis_upload == 'file') {
                if ($request->file('link_file')) {
                    $file_path = public_path('product_digital/berkas/'.$product->link_file);
                    File::delete($file_path);
                    $files = $request->file('link_file');
                    $namefile = $files->getClientOriginalName();
                    $files->move('product_digital/berkas/',$namefile);
                    // $input['link_file'] = $namefile;
                    $input['link_file'] = asset('product_digital/berkas/'.$namefile);
                }
            }

            // $input['link_file'] = $request->link_file;
            $input['keywords'] = $request->keywords;

            $product->update($input);

            if($product){
                return redirect()->route('products')
                ->with('success','Product '.$input['name'].' update successfully');
            }

        }
        return redirect()->route('products.edit',['slug' => $slug, 'id' => $id])
            ->with(['error' => $validator->errors()->all()]);
    }

    public function delete(Request $request, $slug, $id)
    {
        $product = $this->product->find($id);
        if (empty($product)) {
            return redirect()->back()->with(['error' => 'Data Tidak Ditemukan']);
        }
        $image_path = public_path('product_digital/'.$product->image);
        File::delete($image_path);
        $product->delete();

        return redirect()->route('products')->with(['success' => 'Data Berhasil Dihapus']);
    }

    public function checkout($slug,$id)
    {
        $data['product'] = $this->product->where('slug',$slug)->where('id',$id)->first();
        if (empty($data['product'])) {
            return redirect()->back()->with(['error' => 'Data tidak ditemukan']);
        }

        if ($data['product']['qty'] == 0) {
            return redirect()->back()->with(['error' => 'Stock '.$data['product']['name'].' Telah habis']);
        }

        $data['channels'] = json_decode($this->tripay_payment->getPayment())->data;
        // dd($data);
        return view('products.user.checkout',$data);
    }

    public function checkout_buy(Request $request,$slug,$id)
    {
        // dd($request->all());
        DB::beginTransaction();
        $product = $this->product->where('slug',$slug)->where('id',$id)->first();
        if ($product->qty == 0) {
            return redirect()->back()->with(['error' => 'Stock '.$data['product']['name'].' Telah habis']);
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
            $noIdProductDetail = $this->product_detail->max('id');
            if (empty($noIdProductDetail)) {
                $no_id = 1;
            }else{
                $no_id = $noIdProductDetail+1;
            }
            $this->orders_license->create([
                'id' => $no_id,
                'order_id' => $input['id'],
                'product_id' => $product->id,
                'status' => 'Waiting',
                'user_generate' => auth()->user()->generate
            ]);
            // $input2['id'] = Str::uuid()->toString();
            // $input2['orders_id'] = $input['id'];
            // $input2['order_name'] = $product->name;
            // $input2['qty'] = 1;
            // $input2['price'] = $input['price'];

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
}
