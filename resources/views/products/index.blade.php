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
    Product
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Product</h2>
            </div>
        </div>
        <div class="col-lg-12">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="btn-group mt-2 mb-2 pull-right">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Buat Product</a>
                    </div>
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><img src="{{ asset('product_digital/'.$product->image) }}" width="200"></td>
                                    <td>
                                        <div>{{ $product->name }}</div>
                                        <div>Kategori : {{ $product->category->name.' - '.$product->category->category_detail->name }}</div>
                                    </td>
                                    <td>{{ 'Rp. '.number_format($product->price,0,',','.') }}</td>
                                    <td>{{ $product->qty }}</td>
                                    <td>
                                        <a href="{{ route('products.detail',['slug' => $product->slug, 'id' => $product->id]) }}"
                                            class="btn btn-info"><i class="fas fa-eye"></i> Detail Product
                                        </a>
                                        <a href="{{ route('products.edit',['slug' => $product->slug, 'id' => $product->id]) }}"
                                            class="btn btn-warning"><i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- <a href="{{ route('category.edit', ['id' => $category->id]) }}"
                                            class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('category.delete', ['id' => $category->id]) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>
                                                Delete</button>
                                        </form> --}}
                                        <form action="{{ route('products.delete', ['slug' => $product->slug, 'id' => $product->id]) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>
                                                Delete</button>
                                        </form>
                                        {{-- <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>
                                            Delete</button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
