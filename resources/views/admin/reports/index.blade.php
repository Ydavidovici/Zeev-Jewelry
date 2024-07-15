@extends('admin.layout')

@section('content')
    <h1>Analytics and Reports</h1>
    <div>
        {!! $chart->html() !!}
    </div>
    {!! Charts::scripts() !!}
    {!! $chart->script() !!}
@endsection
