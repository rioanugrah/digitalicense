@extends('layouts.backend.master')
@section('css')
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables.net-buttons-bs4/datatables.net-buttons-bs4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}"
        rel="stylesheet" type="text/css" />
@endsection
@section('title')
    Product {{ $product->name }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <h4>Product : {{ $product->name }}</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Image</th>
                                <td class="text-center">:</td>
                                <td><img src="{{ asset('product_digital/' . $product->image) }}" width="200"></td>
                            </tr>
                            <tr>
                                <th>Product Name</th>
                                <td class="text-center">:</td>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td class="text-center">:</td>
                                <td>{!! $product->description !!}</td>
                            </tr>
                            <tr>
                                <th>Category Product</th>
                                <td class="text-center">:</td>
                                <td>{{ $product->category->name.' - '.$product->category->category_detail->name }}</td>
                            </tr>
                            <tr>
                                <th>Quantity</th>
                                <td class="text-center">:</td>
                                <td>{{ $product->qty }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td class="text-center">:</td>
                                <td>{{ 'Rp. ' . number_format($product->price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Action</th>
                                <td class="text-center">:</td>
                                <td>
                                    <a href="{{ route('products.edit',['slug' => $product->slug, 'id' => $product->id]) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="mb-3">
                        <h4>Product Detail</h4>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Lisence</th>
                                    <th>Status</th>
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->product_detail as $key => $product_detail)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $product_detail->product_license }}</td>
                                        <td class="text-center">
                                            @php
                                                switch ($product_detail->status) {
                                                    case 'Open':
                                                        $status = '<span class="badge bg-primary">OPEN</span>';
                                                        break;
                                                    case 'Used':

                                                        break;

                                                    default:

                                                        break;
                                                }
                                            @endphp
                                            {!! $status !!}
                                            {{-- {{ $product_detail->status }} --}}
                                        </td>
                                        <td class="text-center">{{ !$product_detail->user ? null : $product_detail->user->name }}</td>
                                        <td>
                                            @if ($product_detail->user_generate)
                                                <a href="#" class="btn btn-info">Create License Key</a>
                                                <a href="#" class="btn btn-warning">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/datatables.net/datatables.net.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons/datatables.net-buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-buttons-bs4/datatables.net-buttons-bs4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.js') }}">
    </script>
    <script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection
