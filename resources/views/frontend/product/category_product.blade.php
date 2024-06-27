@extends('layouts.frontend.master')
@section('title')
    Product {{ $categories->name }}
@endsection
@section('content')
    <div class="row">
        <h3 class="col-xl-12 col-md-12 mb-3">
            Product {{ $categories->name }}
        </h3>
        @foreach ($categories->product as $product)
            <div class="col-xl-3 col-md-3">
                <button class="card" onclick="window.location.href='{{ route('frontend.product_detail',['category' => $categories->slug, 'category_id' => $categories->category_detail->id, 'slug' => $product->slug]) }}'">
                {{-- <button class="card"> --}}
                    <img class="card-img-top img-fluid" src="{{ asset('product_digital/' . $product->image) }}"
                        alt="{{ $product->name }}">
                    <div class="card-body">
                        <h4 class="card-title">{{ $product->name }}</h4>
                    </div>
                    <div class="card-footer">
                        <div style="font-weight: bold; font-size: 18pt">
                            {{ 'Rp. ' . number_format($product->price, 0, ',', '.') }}
                        </div>
                    </div>
                </button>
            </div>
        @endforeach
    </div>
@endsection
