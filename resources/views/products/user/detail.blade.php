@extends('layouts.backend.master')
@section('title')
    {{ $product->name }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="product-detail-imgs">
                                <div class="row">
                                    <div class="col-md-9">
                                        <img src="{{ URL::asset('product_digital/'.$product->image) }}" alt="{{ $product->name }}" class="img-fluid mx-auto d-block">
                                        <div class="text-center">
                                            <a href="{{ route('products.checkout',['slug' => $product->slug, 'id' => $product->id]) }}" class="btn btn-success waves-effect  mt-2 waves-light">
                                                <i class="bx bx-shopping-bag me-2"></i>Buy now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="mt-4 mt-xl-3">
                                <a href="javascript: void(0);" class="text-primary">{{ $product->category->name }}</a>
                                <h4 class="mt-1 mb-3">{{ $product->name }}</h4>
                                <h5 class="mb-4">Price : <span class="text-muted me-2"><b>{{ 'Rp. '.number_format($product->price,0,',','.') }}</b></h5>
                                <div>{!! $product->description !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
