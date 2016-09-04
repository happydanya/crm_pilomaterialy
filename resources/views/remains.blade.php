<?php
/**
 * Author: Dmitriev V. Daniil
 * Date: 14.08.2016
 * Time: 4:34
 */
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="title panel-title">
                        {{ $title_heading }}
                    </span>
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
                        @if(count($products) != 0 && $products != '')
                            <div class="table-responsive">
                                <table id="main-table" class="table-bordered table-hover col-md-10 col-md-offset-1 text-center">
                                    <thead>
                                    <tr>
                                        <td class="col-md-1">
                                            @lang('products.id')
                                        </td>
                                        <td class="col-md-5">
                                            @lang('products.name')
                                        </td>
                                        <td class="col-md-1
                                            @if($_SERVER['REQUEST_URI'] == '/sortByDateRemains') text-info link-button @endif"
                                            @if($_SERVER['REQUEST_URI'] == '/sortByDateRemains') id="showInfoCount" @endif >
                                            @if($_SERVER['REQUEST_URI'] == '/sortByDateRemains') <b> @endif

                                                @lang('products.quantity') (@lang('products.unit_count'))

                                            @if($_SERVER['REQUEST_URI'] == '/sortByDateRemains') </b> @endif
                                        </td>
                                        @if($_SERVER['REQUEST_URI'] == '/sortByDateRemains')
                                        <td class="col-md-1">
                                            @lang('products.coming') (@lang('products.unit_count'))
                                        </td>
                                        <td class="col-md-1">
                                            @lang('products.consumption') (@lang('products.unit_count'))
                                        </td>
                                        @endif
                                        <td class="col-md-3">
                                            @lang('products.price') (@lang('products.unit_price'))
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td class="link-button" data-toggle="modal" data-target="#modal-{{ $product->id }}">
                                                {{ $product->id }}
                                            </td>
                                            <td class="link-button" data-toggle="modal" data-target="#modal-{{ $product->id }}">
                                                {{ $product->name }}
                                            </td>
                                            <td>
                                                {{ $product->count }}
                                            </td>
                                            @if($_SERVER['REQUEST_URI'] == '/sortByDateRemains')
                                            <td>
                                                {{ $product->coming }}
                                            </td>
                                            <td>
                                                {{ $product->consumption }}
                                            </td>
                                            @endif
                                            <td>
                                                {{ $product->price }}
                                            </td>
                                        </tr>
                                        <div id="modal-{{ $product->id }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">
                                                            @lang('products.product_num')
                                                            {{ $product->id }}
                                                        </h4>
                                                        <h4 class="modal-title">
                                                            @lang('products.product_name'):
                                                            {{ $product->name }}
                                                        </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>
                                                            @lang('products.count_of_product'):
                                                                {{ $product->count }}
                                                            @lang('products.unit_count')
                                                        </p>
                                                        <p>
                                                            @lang('products.price_of_product'):
                                                                {{ $product->price }}
                                                            @lang('products.unit_price')
                                                        </p>
                                                        @if($_SERVER['REQUEST_URI'] == '/sortByDateRemains')
                                                            <p>
                                                                @lang('products.coming') (@lang('products.unit_count')):
                                                                {{ $product->coming }}
                                                            </p>
                                                            <p>
                                                                @lang('products.consumption') (@lang('products.unit_count')):
                                                                {{ $product->consumption }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                                            @lang('products.close')
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div>
                            <form class="form-horizontal col-md-12" method="post" action="{{ $url_date }}">
                                <h3 class="col-md-12 col-md-offset-2">
                                    @lang('remains.sort_by_date')
                                </h3>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="from_date">
                                        @lang('remains.from'):
                                    </label>
                                    <div class="col-md-6">
                                        <input placeholder="@lang('remains.placeholder_date')"
                                               pattern="[0-9]+[0-9]+-[0-9]+[0-9]+-[0-3]+[0-9]"
                                               class="col-md-10" type="date" name="from_date" id="from_date">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="to_date">
                                        @lang('remains.to'):
                                    </label>
                                    <div class="col-md-6">
                                        <input placeholder="@lang('remains.placeholder_date')"
                                               pattern="[0-9]+[0-9]+-[0-9]+[0-9]+-[0-3]+[0-9]"
                                               class="col-md-10" type="date" name="to_date" id="to_date">
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-md-8">
                                        <input type="submit"
                                               value="@lang('remains.btn_date_submit')"
                                               class="btn btn-default col-md-3">
                                        <button type="button"
                                                class="col-sm-offset-1 btn btn-default col-md-3" id="print">
                                            @lang('remains.print_table')
                                        </button>
                                        @if($_SERVER['REQUEST_URI'] == '/sortByDateRemains')
                                            <a href="{{ url('/remains') }}">
                                                <button type="button" class="col-sm-offset-1 btn btn-default col-md-2">
                                                    @lang('remains.back')
                                                </button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection