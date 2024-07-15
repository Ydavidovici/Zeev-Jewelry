@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Payment Record</h1>

        <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="order_id">Order ID</label>
                <input type="text" name="order_id" class="form-control" value="{{ $payment->order_id }}">
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="text" name="amount" class="form-control" value="{{ $payment->amount }}">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" class="form-control" value="{{ $payment->status }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
