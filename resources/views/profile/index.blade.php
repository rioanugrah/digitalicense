@extends('layouts.backend.master')
@section('title')
    Profile
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Profile</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="">Name</label>
                                    <div>{{ $user->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="">Email</label>
                                    <div>{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="">Change Password</label>
                                    <div><input type="password" name="password" class="form-control"
                                            placeholder="Change Password" id=""></div>
                                </div>
                            </div>
                            @if (empty($user->telegram_id))
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="">Telegram ID</label>
                                    <div><input type="text" name="telegram_id" class="form-control"
                                            placeholder="Telegram ID" id=""></div>
                                </div>
                            </div>
                            @else
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="">Telegram ID</label>
                                    <div>{{ $user->telegram_id }}</div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div><button type="submit" class="btn btn-success">Submit</button></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
