@extends('layouts.backend.master')
@section('title')
    Dashboard
@endsection
@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Order Hari Ini</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $order_day->count() }}">{{ $order_day->count() }}</span>
                        </h4>
                        {{-- <div class="text-nowrap">
                            <span class="badge bg-danger-subtle text-danger">-29 Trades</span>
                            <span class="ms-1 text-muted font-size-13">Since last week</span>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Items</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="{{ $product->count() }}">{{ $product->count() }}</span>
                        </h4>
                        {{-- <div class="text-nowrap">
                            <span class="badge bg-danger-subtle text-danger">-29 Trades</span>
                            <span class="ms-1 text-muted font-size-13">Since last week</span>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Points (Rp.)</span>
                        <h4 class="mb-3">
                            <span class="counter-value" data-target="0">0</span>
                        </h4>
                        {{-- <div class="text-nowrap">
                            <span class="badge bg-danger-subtle text-danger">-29 Trades</span>
                            <span class="ms-1 text-muted font-size-13">Since last week</span>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 col-md-12">
        <div class="card">
            {{-- <div class="card-header">
                <h4 class="card-title mb-0">Spline Area</h4>
            </div> --}}
            <div class="card-body">
                <div id="spline_area" data-colors='["#1c84ee", "#34c38f"]' class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <!-- apexcharts js -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- apexcharts init -->
    <script src="{{ asset('assets/js/pages/apexcharts.init.js') }}"></script>
@endsection
