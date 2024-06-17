@extends('layouts.frontend.master')
@section('title')
    Digital License
@endsection
@section('css')
@endsection
@section('content')
    <div class="row">
        @foreach ($categories->paginate(5) as $category)
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1 style="font-size: 12pt">{{ $category->name }} <a style="font-weight: 100; color: blue">See All</a>
                        </h1>
                        <div class="row">
                            @foreach ($category->product as $product)
                                <div class="col-xl-3 col-md-6">
                                    <button class="card"
                                        onclick="window.location.href='{{ route('frontend.product_detail', ['category' => $category->slug, 'slug' => $product->slug]) }}'">
                                        <img class="card-img-top img-fluid"
                                            src="{{ asset('product_digital/' . $product->image) }}"
                                            alt="{{ $product->name }}">
                                        <div class="card-body">
                                            <h4 class="card-title">{{ $product->name }}</h4>
                                            <div style="font-weight: bold; font-size: 18pt">
                                                {{ 'Rp. ' . number_format($product->price, 0, ',', '.') }}</div>
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('script')
@endsection
