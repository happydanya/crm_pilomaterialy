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
                        @if(count($products) != 0)
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
                                        <td class="col-md-3">
                                            @lang('products.price') (@lang('products.unit_price'))
                                        </td>
                                        @if ( Auth::user()->status == 0)
                                            <td class="col-md-1"></td>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td style="cursor: pointer" data-toggle="modal" data-target="#modal-{{ $product->id }}">
                                            {{ $product->id }}
                                        </td>
                                        <td style="cursor: pointer" data-toggle="modal" data-target="#modal-{{ $product->id }}">
                                            {{ $product->name }}
                                        </td>
                                        <td>
                                            {{ $product->price }}
                                        </td>
                                        @if ( Auth::user()->status == 0)
                                            <td>
                                                    <span style="cursor: pointer"
                                                          onclick="deleteProduct('{{ $product->id }}')"
                                                          class="link-button glyphicon glyphicon-remove-circle"></span>
                                            </td>
                                        @endif
                                    </tr>
                                    <div id="modal-{{ $product->id }}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">
                                                        @lang('products.product_num'){{ $product->id }}
                                                    </h4>
                                                    <h4 class="modal-title">
                                                        @lang('products.product_name'): {{ $product->name }}
                                                    </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>@lang('products.count_of_product'):
                                                        {{ $product->count }}
                                                        @lang('products.unit_count')</p>
                                                    <p>@lang('products.price_of_product'):
                                                        {{ $product->price }}
                                                        @lang('products.unit_price')</p>
                                                    <p>@lang('products.total_of_product'):
                                                        {{ floatval($product->price) * intval($product->count) }} грн.</p>
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
                        <form id="product-form" class="form-horizontal col-md-12" style="padding-top: 2rem" role="form">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">
                                    @lang('products.name_label') <span style="color: red">*</span>:
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="col-sm-10" name="name" id="name"
                                           placeholder="@lang('products.name_placeholder')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">
                                    @lang('products.description_label'):
                                </label>
                                <div class="col-sm-10">
                                <textarea name="description" class="col-sm-10" id="description"
                                          placeholder="@lang('products.description_placeholder')"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="count">
                                    @lang('products.quantity_label') <span style="color: red">*</span>:
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="col-sm-10" name="count" id="count"
                                           placeholder="@lang('products.quantity_placeholder')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="price">
                                    @lang('products.price_label') <span style="color: red">*</span>:
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" class="col-sm-10" name="price" id="price"
                                           placeholder="@lang('products.price_placeholder')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-5">
                                    @lang('products.fields')
                                    (<span style="color: red">*</span>)
                                    @lang('products.required')
                                </label>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-4">
                                    <input class="btn btn-default" type="button"
                                           onclick="addProduct()" value="@lang('products.add_product_btn')">
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-scripts')
    <script>
        function addProduct() {
            if ($('input#name').val() != ''
                    && $('input#count').val() != ''
                    && $('input#price').val() != '') {
                $.ajax({
                    url: '{{ $url }}',
                    type: 'post',
                    data: $('#product-form').serialize(),
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    'products.title_of_modal',
                                    '@lang('products.success')',
                                    'success'
                            );
                            refreshTable('{{ $url }}', '#main-table');
                        } else if (data == 'false') {
                            customPopUpText(
                                    'products.title_of_modal',
                                    '@lang('products.error')',
                                    'err'
                            );
                        } else {
                            customPopUpText(
                                    'products.title_of_modal',
                                    data,
                                    'err'
                            );
                        }
                    }
                });
            } else {
                if($('input#name').val() == '') {
                    customPopUpText(
                            'products.title_of_modal',
                            '@lang('products.no_name_error')',
                            'err'
                    );
                } else if($('input#count').val() == '') {
                    customPopUpText(
                            'products.title_of_modal',
                            '@lang('products.no_quantity_error')',
                            'err'
                    );
                } else if($('input#price').val() == '') {
                    customPopUpText(
                            'products.title_of_modal',
                            '@lang('products.no_price_error')',
                            'err'
                    );
                } else {
                    customPopUpText(
                            'products.title_of_modal',
                            '@lang('products.no_data_error')',
                            'err'
                    );
                }
            }
        }
        function deleteProduct(id) {
            if (id != '') {
                $.ajax({
                    url: '{{ $url_delete }}',
                    type: 'post',
                    data: 'id='+id+'&_token={{ csrf_token() }}',
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    'products.title_of_modal',
                                    '@lang('products.success_delete')',
                                    'success'
                            );
                            refreshTable('{{ $url }}', '#main-table');
                        } else if (data == 'false') {
                            customPopUpText(
                                    'products.title_of_modal',
                                    '@lang('products.error_delete')',
                                    'err'
                            );
                        } else {
                            customPopUpText(
                                    'products.title_of_modal',
                                    data,
                                    'err'
                            );
                        }
                    }
                });
            }
        }

        function plus(id) {
            if (id != '') {
                var count = parseInt($('#count_of_prod_'+id).html()) + 1;
                $.ajax({
                    url: '{{ url('/updateCountProduct') }}',
                    type: 'post',
                    data: 'id='+id+'&_token={{ csrf_token() }}&count='+count,
                    success: function (data) {
                        if (data == 'true') {
                            $('#count_of_prod_'+id).html(count);
                        } else if (data == 'false') {
                            customPopUpText(
                                    'products.title_of_modal',
                                    '@lang('products.error_plus')',
                                    'err'
                            );
                        }
                    }
                });
            }
        }
        function minus(id) {
            if (id != '') {
                var count = parseInt($('#count_of_prod_'+id).html()) - 1;
                if(count <= 0) {
                    customPopUpText(
                            'products.title_of_modal',
                            '@lang('products.error_minus_low')',
                            'err'
                    );
                } else {
                    $.ajax({
                        url: '{{ url('/updateCountProduct') }}',
                        type: 'post',
                        data: 'id='+id+'&_token={{ csrf_token() }}&count='+count,
                        success: function (data) {
                            if (data == 'true') {
                                $('#count_of_prod_'+id).html(count);
                            } else if (data == 'false') {
                                customPopUpText(
                                        'products.title_of_modal',
                                        '@lang('products.error_plus')',
                                        'err'
                                );
                            }
                        }
                    });
                }
            }
        }
    </script>
@endsection