@extends('layouts.backend.master')

@section('title')
    Management User
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Users Management</h2>
            </div>
            {{-- <div class="pull-right">
                <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
            </div> --}}
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
                </div>
                <div class="mb-3">
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($data as $key => $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $model_has_role = \DB::table('model_has_roles')->select('role_id')->where('model_id',$user->id)->first();
                                        // dd($model_has_role->role_id);
                                        $roles = \DB::table('roles')->find($model_has_role->role_id);
                                        // dd($roles);
                                    @endphp
                                    @switch($roles->name)
                                        @case('Admin')
                                            <span class="badge bg-primary">{{ $roles->name }}</span>
                                            @break
                                        @case('User')
                                            <span class="badge bg-info">{{ $roles->name }}</span>
                                            @break
                                        @default

                                    @endswitch
                                    {{-- @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $v)
                                            <label class="badge badge-success">{{ $v }}</label>
                                        @endforeach
                                    @endif --}}
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('users.show', $user->id) }}">Show</a>
                                    <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- {!! $data->render() !!}


<p class="text-center text-primary"><small>Tutorial by ItSolutionStuff.com</small></p> --}}
@endsection
