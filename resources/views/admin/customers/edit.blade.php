@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Customer</h1>
        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="user_id">User</label>
                <input type="text" name="user_id" class="form-control" value="{{ $customer->user_id }}" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" value="{{ $customer->address }}" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" value="{{ $customer->phone_number }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
            </div>
            <div class="form-group">
                <label for="is_guest">Is Guest</label>
                <select name="is_guest" class="form-control" required>
                    <option value="0" {{ $customer->is_guest == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $customer->is_guest == 1 ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>
    </div>
@endsection
