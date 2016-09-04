@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="title panel-title">{{ $title_heading }}</span>
                </div>
                <div class="panel-body">
                    @if (Auth::guest())
                            @lang('home.is_not_login')
                            @lang('home.please')
                            <a href="{{ url('/register') }}">
                                @lang('home.register')
                            </a> или
                            <a href="{{ url('/login') }}">
                                @lang('home.login')
                            </a>!
                    @else
                        <h3>@lang('home.welcome')<b>{{ Auth::user()->name }}</b>!</h3>
                        <h4>@lang('home.make_your_choose')</h4>
                        <div class="list-group">
                            <a href="{{ url('/products') }}" class="list-group-item">
                                @lang('home.table_products')
                            </a>
                            <a href="{{ url('/coming') }}" class="list-group-item">
                                @lang('home.table_comings')
                            </a>
                            <a href="{{ url('/consumption') }}" class="list-group-item">
                                @lang('home.table_consumption')
                            </a>
                            <a href="{{ url('/remains') }}" class="list-group-item">
                                @lang('home.table_remains')
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
