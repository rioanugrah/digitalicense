@extends('layouts.backend.master')

@section('title')
    Create Roles
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 mt-2 mb-2">
            <div class="pull-left">
                <h2>Create New Role</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
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
                    {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Permission:</strong>
                                {{-- <br /> --}}
                                <div class="row mt-2 mb-2">
                                    @foreach ($custom_permission as $key => $group)
                                    <div class="col-md-6">
                                        <label style="font-weight: bold">{{ ucfirst($key) }}</label>
                                        <div>
                                            @forelse($group as $permission)
                                            <label class="">
                                                <input name="permission[]" class="rounded-md border" type="checkbox" value="{{ $permission->id }}">
                                                {{$permission->name}} &nbsp;&nbsp;
                                            </label>
                                            @empty
                                                {{ __("No permission in this group !") }}
                                            @endforelse
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                {{-- @foreach ($permission as $value)
                                    <label>{{ Form::checkbox('permission[]', $value->id, false, ['class' => 'name']) }}
                                        {{ $value->name }}</label>
                                    <br />
                                @endforeach --}}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
