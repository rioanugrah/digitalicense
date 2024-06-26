@extends('layouts.backend.master')
@section('title')
    Buat Produk
@endsection
@section('css')
{{-- <link href="{{ URL::asset('assets/libs/choices.js/choices.js.min.css') }}" rel="stylesheet"> --}}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Create New Product</h2>
            </div>
            <div class="pull-right">
                {{-- <a class="btn btn-primary" href="{{ route('permissions') }}"> Back</a> --}}
            </div>
        </div>
    </div>


    @if (count($errors) > 0)
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
    @endif

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('products.simpan') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="">Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Product Name" id="">
                    </div>
                    <div class="mb-3">
                        <label for="">Category</label>
                        <select name="category_id" class="form-control" id="category_id">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="">Category Detail</label>
                        <select name="category_detail_id" class="form-control" id="category_detail_id">
                            <option value="">-- Select Category Detail --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="">Description</label>
                        <textarea name="description" id="ckeditor-classic" cols="30" rows="10"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="">Quantity</label>
                        <input type="text" name="qty" class="form-control" placeholder="Quantity" id="">
                    </div>
                    <div class="mb-3">
                        <label for="">Price</label>
                        <input type="text" name="price" class="form-control" placeholder="Price" id="">
                    </div>
                    <div class="mb-3">
                        <label for="">Upload Image</label>
                        <input type="file" name="image" class="form-control" id="">
                    </div>
                    <div class="mb-3">
                        <label for="">Jenis Upload</label>
                        <select name="jenis_upload" class="form-control" id="jenis_upload">
                            <option value="">-- Pilih Jenis Upload --</option>
                            <option value="url">URL</option>
                            <option value="file">File</option>
                        </select>
                    </div>
                    <div class="mb-3" id="hasil_jenis_upload"></div>
                    {{-- <div class="mb-3">
                        <label for="">Upload File</label>
                        <input type="file" name="link_file" class="form-control" id="">
                    </div> --}}
                    <div class="mb-3">
                        <label for="">Keywords</label>
                        <input type="text" name="keywords" class="form-control" placeholder="Keywords">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('products') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
                {{-- {!! Form::open(['route' => 'category.simpan', 'method' => 'POST']) !!}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <strong>Name:</strong>
                            {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a class="btn btn-secondary" href="{{ route('category') }}"> Back</a>
                    </div>
                </div>
                {!! Form::close() !!} --}}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/@ckeditor/@ckeditor.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script>
        $('#jenis_upload').on('change',function(){
            // alert($('#jenis_upload').val());
            if ($('#jenis_upload').val() == 'url') {
                document.getElementById('hasil_jenis_upload').innerHTML = '<label for="">Link File</label>'+'<input type="text" name="link_file" class="form-control" placeholder="Link File">';
            }else if($('#jenis_upload').val() == 'file') {
                document.getElementById('hasil_jenis_upload').innerHTML = '<label for="">Upload File</label>'+'<input type="file" name="link_file" class="form-control">';
            }
        });

        $('#category_id').on('change', function(){
            $.ajax({
                type:'GET',
                url: "{{ url('b/products/category_detail/') }}"+'/'+$('#category_id').val(),
                contentType: false,
                processData: false,
                success: (result) => {
                    // alert(result);
                    if (result.success == true) {
                        var category_detail_list = result.data.category_detail_list;
                        var txt = "";
                        category_detail_list.forEach(dataCategoryDetail);

                        function dataCategoryDetail(value,index) {
                            txt = txt+"<option value="+value.id+">"+value.name+"</option>";
                        }

                        // $('#category_detail_id').val(txt);
                        document.getElementById('category_detail_id').innerHTML = txt;
                    }else{
                        document.getElementById('category_detail_id').innerHTML = '<option value="">-- Select Category Detail --</option>';
                    }
                },
                error: function (request, status, error) {

                }
            });
        });
    </script>
    {{-- <script src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js') }}"></script> --}}
@endsection
