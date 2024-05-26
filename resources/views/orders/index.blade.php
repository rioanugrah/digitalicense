@extends('layouts.backend.master')
@section('title')
    Orders
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables.net-buttons-bs4/datatables.net-buttons-bs4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}"
        rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>@yield('title')</h2>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Billing Name</th>
                                <th class="text-center">Order Date</th>
                                <th class="text-center">Order Detail</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $key => $order)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>{{ json_decode($order->billing_order)->name }}</td>
                                    <td class="text-center">{{ $order->created_at }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($order->order_detail as $order_detail)
                                            <li>
                                                <div>Order Name : {{ $order_detail->order_name }}</div>
                                                <div>Qty : {{ $order_detail->qty }}</div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-center">{{ 'Rp. '.number_format($order->price,0,',','.') }}</td>
                                    <td class="text-center">
                                        @php
                                            switch ($order->status) {
                                                case 'Unpaid':
                                                    $status = '<span class="badge bg-warning">UNPAID</span>';
                                                    break;
                                                case 'Paid':
                                                    $status = '<span class="badge bg-success">PAID</span>';
                                                    break;
                                                case 'Expired':
                                                    $status = '<span class="badge bg-danger">EXPIRED</span>';
                                                    break;
                                                case 'Not Paid':
                                                    $status = '<span class="badge bg-danger">NOT PAID</span>';
                                                    break;

                                                default:
                                                    # code...
                                                    break;
                                            }
                                        @endphp
                                        {!! $status !!}
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.detail',['order_code' => $order->order_code, 'id' => $order->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Detail</a>
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
    {{-- <script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script> --}}
    <script>
        $('#datatable').DataTable({
            // 'order':[[6,'desc']]
        });
    </script>
@endsection
