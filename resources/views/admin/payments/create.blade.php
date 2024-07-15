@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Payment Record</h1>

        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="order_id">Order ID</label>
                <input type="text" name="order_id" class="form-control" value="{{ old('order_id') }}">
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="text" name="amount" class="form-control" value="{{ old('amount') }}">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" class="form-control" value="{{ old('status') }}">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
