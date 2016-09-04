<?php
/**
 * Author: Dmitriev V. Daniil
 * Date: 14.08.2016
 * Time: 2:42
 */
?>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">@lang('menu.navigation')</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="{{ url('/') }}">
                <span>
                    <span style="color: blue;">eM</span><span style="color: red;">iS</span>
                </span>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                    <li>
                        <a href="{{ url('/home') }}">
                            @lang('menu.main')
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ url('/home') }}">
                            @lang('menu.main')
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/products') }}">
                            @lang('menu.products')
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/coming') }}">
                            @lang('menu.coming')
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/consumption') }}">
                            @lang('menu.consumption')
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/remains') }}">
                            @lang('menu.remains')
                        </a>
                    </li>
                @endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li>
                        <a href="{{ url('/login') }}">
                            @lang('menu.login')
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/register') }}">
                            @lang('menu.register')
                        </a>
                    </li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            @if( Auth::user()->status != '0')
                                <li>
                                    <a href="{{ url('/raisingStatus') }}">
                                        <i class="fa fa-btn fa-arrow-up"></i>
                                        @lang('menu.raisingStatus')
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="link-button" id="inviteButton">
                                    <i class="fa fa-btn fa-envelope"></i>
                                    @lang('menu.invite')
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/logout') }}">
                                    <i class="fa fa-btn fa-sign-out"></i>
                                    @lang('menu.logout')
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
