@extends('layouts.mainlayout')

@section('title', 'Users')

@section('content')
    <h1>Detail User</h1>

    <div class="mt-3 d-flex justify-content-end">
        @if ($user->status == 'inactive')
        <a href="/user-approve/{{$user->slug}}" class="btn btn-info">Approved User</a>
        @endif
    </div>

    <div class="mt-3">
        @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
    </div>

    <div class="my-3 w-50">
        <div class="mb-3">
            <label for="" class="form-label">Username</label>
            <input type="text" class="form-control" readonly value="{{$user->username}}">
        </div>
        <div class="my-3">
            <div class="mb-3">
                <label for="" class="form-label">Phone</label>
                <input type="text" class="form-control" readonly value="{{$user->phone}}">
            </div>
            <div class="my-3">
                <div class="mb-3">
                    <label for="" class="form-label">Address</label>
                    <textarea name="" id="" cols="20" rows="5" class="form-control" style="resize:none">{{ $user->address }}</textarea>
                </div>
                <div class="my-3">
                    <div class="mb-3">
                        <label for="" class="form-label">Status</label>
                        <input type="text" class="form-control" readonly value="{{$user->status}}">
        </div>
    </div>
    
    <h1>User Rent Log</h1>

    <div class="mt-3">
        <x-rent-log-table :rentlog='$rent_logs' />
    </div>
@endsection