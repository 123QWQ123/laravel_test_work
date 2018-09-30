@extends('template')

@section('title')
    {{trans('titles.currencies')}}
@endsection

@section('content')
    <div id="accordion">
        @include('currency')
    </div>
@endsection
