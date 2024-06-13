<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Payment\TripayController;
use App\Models\Category;
use App\Models\Product;

class FrontendController extends Controller
{
    function __construct(
        TripayController $tripay_payment,
        Category $category,
        Product $product
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
        $this->category = $category;
        $this->product = $product;
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

    public function product_detail($category,$slug)
    {
        $data['categories'] = $this->category
                                            ->select([
                                              'product.id as id',
                                              'category.slug as slug_category',
                                              'product.slug as slug_product',
                                              'product.name as name',
                                              'product.description as description',
                                              'product.price as price',
                                              'product.qty as qty',
                                              'product.image as image',
                                              'product.created_at as created_at',
                                              'product.updated_at as updated_at',
                                            ])
                                            // ->select('
                                            //     product.id as id,
                                            //     product.slug as slug,
                                            //     product.name as name,
                                            //     product.description as description,
                                            //     product.price as price,
                                            //     product.qty as qty,
                                            //     product.image as image,
                                            //     product.created_at as created_at,
                                            //     product.updated_at as updated_at
                                            // ')
                                            ->leftJoin('product','product.category_id','category.id')
                                            ->where('category.slug',$category)
                                            ->where('product.slug',$slug)
                                            ->first();
        // $data['categories'] = $this->category->with('product_detail')
        //                                     ->where('slug',$category)
        //                                     ->whereHas('product_detail', function($product) use($slug){
        //                                         return $product->where('slug','=','gowilds-travel-tour-booking-wordpress-theme');
        //                                     })
        //                                     ->first();
                                            // dd($data);

        // $data['product'] = $this->product->where('slug',$slug)->first();
        if (empty($data['categories'])) {
            return redirect()->back();
        }
        // $data['product'] = $this->product->where('')
        return view('frontend.product.detail',$data);
    }

    public function product_checkout($category,$slug)
    {
        // $data['categories'] = $this->category->where('slug',$category)
        //                                     ->whereHas('product_detail', function($product) use($slug){
        //                                         $product->where('slug',$slug);
        //                                     })
        //                                     ->first();
        $data['categories'] = $this->category
                                            ->select([
                                              'product.id as id',
                                              'category.slug as slug_category',
                                              'product.slug as slug_product',
                                              'product.name as name',
                                              'product.description as description',
                                              'product.price as price',
                                              'product.qty as qty',
                                              'product.image as image',
                                              'product.created_at as created_at',
                                              'product.updated_at as updated_at',
                                            ])
                                            // ->select('
                                            //     product.id as id,
                                            //     product.slug as slug,
                                            //     product.name as name,
                                            //     product.description as description,
                                            //     product.price as price,
                                            //     product.qty as qty,
                                            //     product.image as image,
                                            //     product.created_at as created_at,
                                            //     product.updated_at as updated_at
                                            // ')
                                            ->leftJoin('product','product.category_id','category.id')
                                            ->where('category.slug',$category)
                                            ->where('product.slug',$slug)
                                            ->first();
        if (empty($data['categories'])) {
            return redirect()->back();
        }
        $data['channels'] = json_decode($this->tripay_payment->getPayment())->data;
        // dd($data);
        return view('frontend.product.checkout',$data);
    }
}
