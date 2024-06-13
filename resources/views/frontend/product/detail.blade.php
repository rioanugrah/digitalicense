@extends('layouts.frontend.master')
@section('title')
    {{ $categories->product_detail->name }}
@endsection
@section('css')

@endsection
@section('content')
    <div class="row">
        <div class="col-xl-5 col-md-12">
            <div class="card">
                <img class="card-img-top img-fluid" src="{{ asset('product_digital/'.$categories->product_detail->image) }}" alt="{{ $categories->product_detail->name }}">
            </div>
        </div>
        <div class="col-xl-7 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $categories->product_detail->name }}</h4>
                    <div class="mt-3 mb-3" style="font-weight: bold; font-size: 18pt">{{ 'Rp. '.number_format($categories->product_detail->price,0,',','.') }}</div>
                    <button class="btn btn-success" onclick="window.location.href='{{ !\Auth::check() ? route('login') : route('frontend.product_checkout',['category' => $categories->slug,'slug' => $categories->product_detail->slug]) }}'"><i class="mdi mdi-cart"></i> Buy</button>
                    <p>{!! $categories->product_detail->description !!}</p>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <p>{{ $categories->product_detail }}</p>
    </div> --}}
@endsection