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
                        @lang('consumption.id')
                    </td>
                    <td class="col-md-3">
                        @lang('consumption.name_of_product')
                    </td>
                    <td class="col-md-1">
                        @lang('consumption.quantity')
                    </td>
                    <td class="col-md-2">
                        @lang('consumption.price')
                    </td>
                    <td class="col-md-2">
                        @lang('consumption.total')
                    </td>
                    @if ( Auth::user()->status == 0)
                        <td class="col-md-1"></td>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($consumption as $product)
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
                            {{ $product->count_of_consumption }}
                        </span>
                        <span class="glyphicon glyphicon-plus col-md-4 link-button pull-right"
                              onclick="plus('{{ $product->unique_id }}')"></span>
                            </td>
                            <td id="price-{{ $product->unique_id }}">
                                @if($product->price_of_consumption != 0)
                                    {{ $product->price_of_consumption }}
                                @elseif(isset($product->price))
                                    {{ $product->price }}
                                @endif
                            </td>
                            <td>
                                @if($product->price_of_consumption != 0)
                                    {{
                                        floatval(
                                            round(floatval($product->price_of_consumption) * intval($product->count_of_consumption), 2)
                                        )
                                    }}
                                @else
                                    {{
                                        floatval(
                                            round(floatval($product->price) * intval($product->count_of_consumption), 2)
                                        )
                                    }}
                                @endif
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
                                @lang('consumption.no_product')
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
            <h3>
                @lang('consumption.add_new_product_of_consumption')
            </h3>
            <div class="form-group">
                <label class="control-label col-sm-4" for="product-name-modal">
                    @lang('consumption.product_label') <span style="color: red">*</span>:
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
                    @lang('consumption.quantity') <span style="color: red">*</span>:
                </label>
                <div class="col-sm-8">
                    <input type="number" class="col-sm-12" name="count-modal" id="count-modal"
                           placeholder="@lang('consumption.quantity_placeholder')">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="price-modal">
                    @lang('consumption.price') <span style="color: red">*</span>:
                </label>
                <div class="col-sm-8">
                    <input type="number" class="col-sm-12" name="price-modal" id="price-modal"
                           placeholder="@lang('consumption.price_placeholder')">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="description-modal">
                    @lang('consumption.description_label')
                </label>
                <div class="col-sm-8">
                    <textarea class="col-sm-12" name="description-modal" id="description-modal"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-5">
                    {!!
                        trans(
                            'consumption.label_notification',
                            ['symbol' => '*']
                        )
                    !!}
                </label>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-3">
                    <input class="btn btn-default" type="button"
                           onclick="addProductModal()" value="@lang('consumption.add_new_product')">
                </div>
                <button type="button" class="col-sm-offset-1 btn btn-default col-md-3" id="printModal">
                    @lang('consumption.print_table')
                </button>
                <button class="col-sm-offset-1 btn btn-default col-md-3"
                        onclick="refreshTable('/consumption', '#main-table')" data-dismiss="modal">
                    @lang('consumption.close')
                </button>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    @endif
@endsection
@section('custom-scripts')
    <script>
        $('#printModal').click(function() {
            if(document.getElementById("main-table-modal") != null) {
                printData("main-table-modal")
            } else {
                customPopUpText(
                        '@lang('consumption.title_of_modal')',
                        '@lang('consumption.no_table')',
                        'err'
                );
            }
        });

        function addProductModal() {
            var count = $('input#count-modal').val();
            var name = $("#product-name-modal").find("option:selected" ).text();
            var price = $('input#price-modal').val();

            if (count != '' && name != '' && price != '')
            {
                $.ajax({
                    url: '{{ $url }}',
                    type: 'post',
                    data: 'count='+count
                    +'&product_id='+name
                    +'&price='+price
                    +'&_token={{ csrf_token() }}',
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    '@lang('consumption.success_add_product')',
                                    'success'
                            );
                            refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalConsumption');
                        } else if (data == 'false') {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    '@lang('consumption.error_add_product')',
                                    'err'
                            );
                        } else {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    data,
                                    'err'
                            );
                        }
                    }
                });
            } else {
                if(name == '') {
                    customPopUpText(
                            '@lang('consumption.title_of_modal')',
                            '@lang('consumption.no_name_error')',
                            'err'
                    );
                } else if(count == '') {
                    customPopUpText(
                            '@lang('consumption.title_of_modal')',
                            '@lang('consumption.no_quantity_error')',
                            'err'
                    );
                } else if(price == '') {
                    customPopUpText(
                            '@lang('consumption.title_of_modal')',
                            '@lang('consumption.no_price_error')',
                            'err'
                    );
                } else {
                    customPopUpText(
                            '@lang('consumption.title_of_modal')',
                            '@lang('consumption.no_data_error')',
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
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    '@lang('consumption.success_delete_product')',
                                    'success'
                            );
                                refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalConsumption');
                        } else if (data == 'false') {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    '@lang('consumption.error_delete')',
                                    'err'
                            );
                        } else {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    data,
                                    'err'
                            );
                        }
                    }
                });
            }
        }

        /**
         * Functions for edit quantity
         * START
         */

        function plus(id) {
            if (id != '') {
                var count = parseInt($('#count_of_prod_'+id).html()) + 1;
                $.ajax({
                    url: '{{ url('/updateCountOfProductFromConsumption') }}',
                    type: 'post',
                    data: 'id='+id+'&_token={{ csrf_token() }}&count='+count,
                    success: function (data) {
                        if (data == 'true') {
                            refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalConsumption');
                        } else if (data == 'false') {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    '@lang('consumption.error_update')',
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
                            '@lang('consumption.title_of_modal')',
                            '@lang('products.error_minus_low')',
                            'err'
                    );
                } else {
                    $.ajax({
                        url: '{{ url('/updateCountOfProductFromConsumption') }}',
                        type: 'post',
                        data: 'id='+id+'&_token={{ csrf_token() }}&count='+count,
                        success: function (data) {
                            if (data == 'true') {
                                refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalConsumption');
                            } else if (data == 'false') {
                                customPopUpText(
                                        '@lang('consumption.title_of_modal')',
                                        '@lang('consumption.error_update')',
                                        'err'
                                );
                            }
                        }
                    });
                }
            }
        }

        /**
         * Functions for edit quantity
         * END
         */

        function submitEdit(id) {
            $.ajax({
                url: '/consumption/'+id,
                type: 'post',
                data: 'name='+ $('#name-edit-'+ id).val() +
                        '&count='+ $('#count-edit-'+ id).val() +
                        '&price=' + $('#price-edit-'+ id).val() +
                        '&_token={{ csrf_token() }}',
                success: function(data) {
                    if(data == 'true') {
                        refreshModal('{{ $_SERVER['REQUEST_URI'] }}', '#ModalConsumption');
                    } else {
                        customPopUpText(
                                '@lang('consumption.title_of_modal')',
                                '@lang('consumption.error_update')',
                                'err'
                        );
                    }
                }
            });
        }
    </script>
@endsection
