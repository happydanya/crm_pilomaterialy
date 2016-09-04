<?php
/**
 * Author: Dmitriev V. Daniil
 * Date: 24.08.16
 * Time: 2:06
 */
?>
@extends('layouts.modal')
@section('content')
        <input type="hidden" class="title-page" value="{{ $title_heading }}">
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
                <table border="1" id="main-table-modal" class="table-bordered col-md-10 col-md-offset-1 text-center">
                    <thead>
                    <tr>
                        <td class="col-md-1">
                            @lang('coming.id')
                        </td>
                        <td class="col-md-3">
                            @lang('coming.name_of_product')
                        </td>
                        <td class="col-md-1">
                            @lang('coming.quantity') (@lang('coming.unit_count'))
                        </td>
                        <td class="col-md-2">
                            @lang('coming.price') (@lang('coming.unit_price'))
                        </td>
                        <td class="col-md-2">
                            @lang('coming.total')
                        </td>
                        @if ( Auth::user()->status == 0)
                            <td class="col-md-1"></td>
                        @endif
                        <!-- <td></td> -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comings as $product)
                    @if(isset($product->name) && $product->name != '')
                        <tr id="editProduct-{{ $product->unique_id }}">
                            <td>
                                {{ $product->unique_id }}
                            </td>
                            <td id="name-{{ $product->unique_id }}">
                                {{ $product->name }}
                            </td>
                            <td id="count-{{ $product->unique_id }}">
                            <span class="glyphicon glyphicon-minus col-md-4 link-button pull-left"
                                  onclick="minus('{{ $product->unique_id }}')"></span>
                            <span class="col-md-1" id="count_of_prod_{{ $product->unique_id }}">
                                {{ $product->count_of_coming }}
                            </span>
                            <span class="glyphicon glyphicon-plus col-md-4 link-button pull-right"
                                  onclick="plus('{{ $product->unique_id }}')"></span>
                            </td>
                            <td id="price-{{ $product->unique_id }}">
                                @if($product->price_of_coming != 0)
                                    {{ $product->price_of_coming }}
                                @else
                                    {{ $product->price }}
                                @endif
                            </td>
                            <td>
                                @if($product->price_of_coming != 0)
                                {{
                                    floatval(
                                            round(floatval($product->price_of_coming) * intval($product->count_of_coming), 2)
                                    )
                                }}
                                @else
                                {{
                                   floatval(
                                           round(floatval($product->price) * intval($product->count_of_coming), 2)
                                   )
                                }}
                                @endif
                                @lang('coming.unit_price')
                            </td>
                            @if ( Auth::user()->status == 0)
                                <td>
                                        <span
                                                onclick="removeComingModal('{{ $product->unique_id }}')"
                                                class="link-button glyphicon glyphicon-remove-circle"></span>
                                </td>
                                @endif
                        </tr>
                    @else
                        <tr id="editProduct-{{ $product->unique_id }}">
                            <td>
                                {{ $product->unique_id }}
                            </td>
                            <td colspan="4">
                                @lang('coming.no_product')
                            </td>
                            @if ( Auth::user()->status == 0)
                                <td>
                                        <span
                                                onclick="removeComingModal('{{ $product->unique_id }}')"
                                                class="link-button glyphicon glyphicon-remove-circle"></span>
                                </td>
                                @endif
                        </tr>
                    @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            <form id="product-form-modal" class="form-horizontal col-md-12" role="form">
                <h3>@lang('coming.add_product_title')</h3>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="product-name-modal">
                        @lang('coming.name_product_label') <span style="color: red">*</span>:
                    </label>
                    <div class="col-sm-8">
                        <select class="form-control" name="product-name-modal" id="product-name-modal">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="count-modal">
                        @lang('coming.quantity') <span style="color: red">*</span>:
                    </label>
                    <div class="col-sm-8">
                        <input type="number" class="col-sm-12" name="count-modal" id="count-modal"
                               placeholder="@lang('coming.placeholder_quantity')">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="price-modal">
                        @lang('coming.price_label') <span style="color: red">*</span>:
                    </label>
                    <div class="col-sm-8">
                        <input type="number" class="col-sm-12" name="price-modal" id="price-modal"
                               placeholder="@lang('coming.placeholder_price')">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="description-modal">
                        @lang('coming.short_description'):
                    </label>
                    <div class="col-sm-8">
                        <textarea class="col-sm-12" name="description-modal" id="description-modal"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-5">
                        @lang('coming.fields')
                        (<span style="color: red">*</span>)
                        @lang('coming.required')
                    </label>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-1 col-sm-3">
                        <input class="btn btn-default" type="button"
                               onclick="addProductModal()" value="@lang('coming.text_btn')">
                    </div>
                    <button type="button" class="col-sm-offset-1 btn btn-default col-md-3" id="printModal">
                        @lang('coming.print_table')
                    </button>
                    <button class="col-sm-offset-1 btn btn-default col-md-3"
                            onclick="refreshTable('/coming', '#main-table')" data-dismiss="modal">
                        @lang('coming.close')
                    </button>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        @endif
@endsection
@section('custom-scripts')
    <script async>
        // Because problem with identification if function in main.js
        $('#printModal').click(function() {
            if(document.getElementById("main-table-modal") != null) {
                printData("main-table-modal")
            } else {
                customPopUpText(
                        '@lang('coming.title_of_modal')',
                        '@lang('coming.error_table_are_not_exist')',
                        'err'
                );
            }
        });

        function addProductModal() {
            if (
                    $('input#count-modal').val() != ''
                    && $("#product-name-modal").find("option:selected" ).text() != ''
                    && $('input#price-modal').val() != ''
            )
            {
                $.ajax({
                    url: '{{ $url }}',
                    type: 'post',
                    data: 'count_of_coming='+$('input#count-modal').val()
                            +'&id='+$("#product-name-modal").find("option:selected" ).val()
                            +'&price='+$('input#price-modal').val()
                            +'&_token={{ csrf_token() }}',
                    error: '@lang('coming.error')',
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    '@lang('coming.title_of_modal')',
                                    '@lang('coming.success')',
                                    'success'
                            );
                            refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalComing');
                        } else if (data == 'false') {
                            customPopUpText(
                                    '@lang('coming.title_of_modal')',
                                    '@lang('coming.error')',
                                    'err'
                            );
                        } else {
                            customPopUpText(
                                    '@lang('coming.title_of_modal')',
                                    data,
                                    'err'
                            );
                        }
                    }
                });
            } else {
                if($('input#product-name-modal').val() == '') {
                    customPopUpText(
                            '@lang('coming.title_of_modal')',
                            '@lang('coming.no_name_error')',
                            'err'
                    );
                } else if($('input#price-modal').val() == '') {
                    customPopUpText(
                            '@lang('coming.title_of_modal')',
                            '@lang('coming.no_price_error')',
                            'err'
                    );
                } else {
                    customPopUpText(
                            '@lang('coming.title_of_modal')',
                            '@lang('coming.no_data_error')',
                            'err'
                    );
                }
            }
        }

        function removeComingModal(id) {
            if (id != '') {
                $.ajax({
                    url: '{{ $url_delete }}',
                    type: 'post',
                    data: 'id_of_product='+id+'&_token={{ csrf_token() }}',
                    error: '@lang('coming.error_deleting')',
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    '@lang('coming.title_of_modal')',
                                    '@lang('coming.success_delete')',
                                    'success'
                            );
                            refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalComing');
                        } else if (data == 'false') {
                            customPopUpText(
                                    '@lang('coming.title_of_modal')',
                                    '@lang('coming.error_deleting')',
                                    'err'
                            );
                        } else {
                            alert(data);
                        }
                    }
                });
            }
        }

        /**
         * Count functions
         * START
         */

        function plus(id) {
            if (id != '') {
                var count = parseInt($('#count_of_prod_'+id).html()) + 1;
                $.ajax({
                    url: '{{ url('/updateCountOfProductFromComing') }}',
                    type: 'post',
                    data: 'id='+id+'&_token={{ csrf_token() }}&count='+count,
                    success: function (data) {
                        if (data == 'true') {
                            refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalComing');
                        } else if (data == 'false') {
                            customPopUpText(
                                    '@lang('coming.title_of_modal')',
                                    '@lang('coming.error_updating')',
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
                    alert('@lang('products.error_minus_low')');
                } else {
                    $.ajax({
                        url: '{{ url('/updateCountOfProductFromComing') }}',
                        type: 'post',
                        data: 'id='+id+'&_token={{ csrf_token() }}&count='+count,
                        success: function (data) {
                            if (data == 'true') {
                                refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalComing');
                            } else if (data == 'false') {
                                customPopUpText(
                                        '@lang('coming.title_of_modal')',
                                        '@lang('coming.error_updating')',
                                        'err'
                                );
                            }
                        }
                    });
                }
            }
        }
        /**
         * Count functions
         * END
         */

        function submitEdit(id) {
            $.ajax({
                url: '/coming/'+id,
                type: 'post',
                data: 'name='+ $('#name-edit-'+ id).val() +
                '&count='+ $('#count-edit-'+ id).val() +
                '&price=' + $('#price-edit-'+ id).val() +
                '&_token={{ csrf_token() }}',
                success: function(data) {
                    if(data == 'true') {
                        refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalComing');
                    } else {
                        customPopUpText(
                                '@lang('coming.title_of_modal')',
                                '@lang('coming.error_updating')',
                                'err'
                        );
                    }
                }
            });
        }
    </script>
@endsection
