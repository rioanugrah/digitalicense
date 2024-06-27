@extends('layouts.backend.master')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Kategori {{ $category->name.' - '.$category->category_detail->name }}</h2>
            </div>
            <div class="pull-right">
                {{-- <a class="btn btn-primary" href="{{ route('permissions') }}"> Back</a> --}}
            </div>
        </div>
    </div>

    {{-- @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    @if ($message = Session::get('error'))
        @foreach ($message as $m)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $m }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif

    @if ($message = Session::get('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Please check the form below for errors
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('category.category_detail_update',['id' => $category->id, 'slug' => $category->slug, 'id_category' => $category->category_detail->id]) }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group mb-3">
                                <strong>Name:</strong>
                                {!! Form::text('name', $category->category_detail->name, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a class="btn btn-secondary" href="{{ route('category.category_detail',['id' => $category->id, 'slug' => $category->slug]) }}"> Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
