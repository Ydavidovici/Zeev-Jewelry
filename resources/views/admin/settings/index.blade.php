@extends('admin.layout')

@section('content')
    <h1>Site Settings</h1>
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        @foreach($settings as $setting)
            <div class="form-group">
                <label for="{{ $setting->key }}">{{ ucfirst($setting->key) }}</label>
                <input type="text" name="settings[{{ $setting->key }}]" id="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control">
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
@endsection
