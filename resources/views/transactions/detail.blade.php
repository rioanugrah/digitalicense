@extends('layouts.backend.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Transaction Detail</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="" style="font-weight: bold">Order Code</label>
                                <div>{{ $order->order_code }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="" style="font-weight: bold">Order Reference</label>
                                <div>{{ $order->order_reference }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="" style="font-weight: bold">Status</label>
                                <div>
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
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="" style="font-weight: bold">Price</label>
                                <div>{{ 'Rp. '.number_format($order->price,0,',','.') }}</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4 class="card-title mb-3">Billing Order</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="">Name</label>
                            <div>{{ json_decode($order->billing_order)->name }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Email</label>
                            <div>{{ json_decode($order->billing_order)->email }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">Phone</label>
                            <div>{{ json_decode($order->billing_order)->phone }}</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Address</label>
                            <div>{{ json_decode($order->billing_order)->address }}</div>
                        </div>
                    </div>
                    <hr>
                    <h4 class="card-title mb-3">Detail Order</h4>
                    {{-- {{ $order->order_detail }} --}}
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->order_detail as $order_detail)
                                <tr>
                                    <td>{{ $order_detail->order_name }}</td>
                                    <td>{{ $order_detail->qty }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                    <a href="{{ route('transactions.check_transaction',['order_code' => $order->order_code, 'order_reference' => $order->order_reference]) }}" class="btn btn-primary">Check Payment</a>
                </div>
            </div>
        </div>
    </div>
@endsection
