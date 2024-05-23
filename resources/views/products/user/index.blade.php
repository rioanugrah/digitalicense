@extends('layouts.backend.master')
@section('css')
    <link href="{{ URL::asset('assets/libs/nouislider/nouislider.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-2">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Filter</h4>
                    <ul class="list-unstyled product-list">
                        @foreach ($categories as $category)
                        <li><a href="javascript: void(0);"><i class="mdi mdi-chevron-right me-1"></i> {{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            <form action="" class="mt-4 mt-sm-0 float-sm-end d-sm-flex align-items-center">
                <div class="search-box me-2">
                    <div class="position-relative">
                        <input type="text" class="form-control border-0" placeholder="Search...">
                        <i class="bx bx-search-alt search-icon"></i>
                    </div>
                </div>
            </form>
            {{-- <div class="row mb-3">
                @foreach ($categories as $category)
                <div class="col-xl-4 col-sm-6">
                    <div class="mt-2">
                        <h5>{{ $category->name }}</h5>
                    </div>
                </div>
                @endforeach
            </div> --}}
            <div class="row mb-3">
                @foreach ($categories as $category)
                <div class="col-xl-4 col-sm-6">
                    <div class="mt-2">
                        <h5>{{ $category->name }}</h5>
                    </div>
                    @foreach ($category->product as $product)
                    <div class="card">
                        <div class="card-body">
                            <div class="product-img position-relative">
                                <img src="{{ URL::asset('product_digital/'.$product->image) }}" alt=""
                                    class="img-fluid mx-auto d-block">
                            </div>
                            <div class="d-flex justify-content-between align-items-end mt-4">
                                <div>
                                    <h5 class="mb-3 text-truncate"><a href="{{ route('products.detail',['slug' => $product->slug, 'id' => $product->id]) }}" class="text-dark">
                                            {{ $product->name }} </a></h5>
                                    <h5 class="my-0"><span class="text-muted me-2">{{ 'Rp. '.number_format($product->price,0,',','.') }}</span></h5>
                                    <h5 class="mt-1">Stock : <span>{{ $product->qty }}</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
                {{-- @foreach ($products as $product)
                <div class="col-xl-4 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="product-img position-relative">
                                <img src="{{ URL::asset('product_digital/'.$product->image) }}" alt=""
                                    class="img-fluid mx-auto d-block">
                            </div>
                            <div class="d-flex justify-content-between align-items-end mt-4">
                                <div>
                                    <h5 class="mb-3 text-truncate"><a href="javascript: void(0);" class="text-dark">Half
                                            {{ $product->name }} </a></h5>
                                    <h5 class="my-0"><span class="text-muted me-2">{{ 'Rp. '.number_format($product->price,0,',','.') }}</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach --}}
            </div>
        </div>
    </div>
@endsection
