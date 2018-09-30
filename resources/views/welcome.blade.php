@extends('template')

@section('title')
    {{trans('titles.auth')}}
@endsection

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            @if(!\Api::isAuth())
                <div class="btn">
                    <a class="btn btn-primary" href="{{ Api::getUrlOauthUser()  }}">@lang('button.login')</a>
                </div>
            @else
                <span>
                @lang('auth.already_logged_in')
            </span>
                <a class="btn btn-primary" href="{{ route('currencies')  }}">@lang('button.currencies')</a>
            @endif
        </div>
    </div>
@endsection
