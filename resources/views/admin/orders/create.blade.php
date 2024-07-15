@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Order</h1>

        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user_id">User</label>
                <select name="user_id" class="form-control">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="text" name="total_amount" class="form-control" value="{{ old('total_amount') }}">
            </div>
            <button type="submit" class="btn btn-primary">Create Order</button>
        </form>
    </div>
@endsection
