<?php
/**
 * Author: Dmitriev V. Daniil
 * Date: 14.08.2016
 * Time: 15:45
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
                        @if($consumption != '' && count($consumption) != 0)
                            <div class="table-responsive">
                                <table border="1" id="main-table" class="table-bordered col-md-10 col-md-offset-1 text-center">
                                    <thead>
                                    <tr>
                                        <td class="col-md-3">
                                            @lang('consumption.date')
                                        </td>
                                        <td class="col-md-3">
                                            @lang('consumption.buyer')
                                        </td>
                                        <td class="col-md-1">
                                            @lang('consumption.count_of_position')
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
                                        <tr id="consumption-{{ $product->id_of_consumption }}">
                                            <td>
                                                {{ date('y-m-d', strtotime($product->date)) }}
                                            </td>
                                            <td>
                                                <a id="name-{{ $product->id_of_consumption }}" class="link-button"
                                                   onclick="getConsumptionByID('{{ $product->id_of_consumption }}')">
                                                    {{ $product->provider }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $product->count }}
                                            </td>
                                            <td>
                                                {{ $product->total }}
                                                @lang('consumption.unit_price')
                                            </td>
                                            @if ( Auth::user()->status == 0)
                                                <td>
                                                            <span
                                                                  onclick="removeComing('{{ $product->id_of_consumption }}')"
                                                                  class="link-button glyphicon glyphicon-remove-circle"></span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        <div>
                            <form class="form-horizontal col-md-12" method="post" action="{{ $url_date }}">
                                <h3 class="col-md-12 col-md-offset-2">
                                    @lang('consumption.sort_by_date')
                                </h3>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="from_date">
                                        @lang('consumption.from'):
                                    </label>
                                    <div class="col-md-6">
                                        <input
                                                placeholder="@lang('consumption.placeholder_date')"
                                                pattern="[0-9]+[0-9]+-[0-9]+[0-9]+-[0-3]+[0-9]"
                                                class="col-md-10" type="date" name="from_date" id="from_date">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="to_date">
                                        @lang('consumption.to'):
                                    </label>
                                    <div class="col-md-6">
                                        <input
                                                placeholder="@lang('consumption.placeholder_date')"
                                                pattern="[0-9]+[0-9]+-[0-9]+[0-9]+-[0-3]+[0-9]"
                                                class="col-md-10" type="date" name="to_date" id="to_date">
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-md-6">
                                        <input type="submit"
                                               value="@lang('consumption.submit_date_btn')"
                                               class="btn btn-default col-md-5">
                                        <button type="button"
                                                class="col-sm-offset-1 btn btn-default col-md-6" id="print">
                                            @lang('consumption.print_table')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                        <form id="product-form" class="form-horizontal col-md-12" role="form">
                            <h3 class="col-md-offset-2">@lang('consumption.add_new_consumption')</h3>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="provider">
                                    @lang('consumption.buyer')
                                    <span style="color: red">*</span>:
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="col-sm-10" name="provider" id="provider"
                                           placeholder="@lang('consumption.placeholder_buyer')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="date_get">
                                    @lang('consumption.label_date'):
                                </label>
                                <div class="col-sm-10">
                                    <input
                                            placeholder="@lang('consumption.placeholder_date')"
                                            pattern="[0-9]+[0-9]+-[0-9]+[0-9]+-[0-3]+[0-9]"
                                            class="col-md-10" type="date" name="date_get" id="date_get">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-5">
                                    @lang('consumption.fields')
                                    (<span style="color: red">*</span>)
                                    @lang('consumption.required')
                                </label>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-1 col-sm-4">
                                    <input class="btn btn-default" type="button" onclick="addProduct()"
                                           value="@lang('consumption.btn_add_consumption')">
                                </div>
                                <?php if($_SERVER['REQUEST_URI'] === '/sortByDateConsumption') { ?>
                                    <a href="{{ $url }}">
                                        <button type="button" class="col-md-4 btn btn-default col-md-3">
                                            @lang('consumption.back_to_consumptions')
                                        </button>
                                    </a>
                                    <button type="button" class="col-sm-offset-1 btn btn-default col-md-3" id="print">
                                        @lang('consumption.print_table')
                                    </button>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                        <div id="ModalConsumption" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" onclick="refreshTable('/consumption', '#main-table')"
                                                class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"></h4>
                                    </div>
                                    <div class="modal-body">
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-scripts')
    <script>
        function addProduct() {
            var input = $('input#provider');
            if (input.val() != '')
            {
                $.ajax({
                    url: '{{ $url }}',
                    type: 'post',
                    data: $('#product-form').serialize(),
                    error: '@lang('consumption.error')',
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    '@lang('consumption.success')',
                                    'success'
                            );
                            refreshTable('{{ $_SERVER['REQUEST_URI'] }}', '#main-table');
                        } else if (data == 'false') {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    '@lang('consumption.error')',
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
                if(input.val() == '') {
                    customPopUpText(
                            '@lang('consumption.title_of_modal')',
                            '@lang('consumption.no_quantity_error')',
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
        function removeComing(id) {
            if (id != '') {
                $.ajax({
                    url: '{{ $url_delete }}',
                    type: 'post',
                    data: 'consumption_id='+id+'&_token={{ csrf_token() }}',
                    error: '@lang('consumption.error_delete')',
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    '@lang('consumption.title_of_modal')',
                                    '@lang('consumption.success_delete')',
                                    'success'
                            );
                            refreshTable('{{ $_SERVER['REQUEST_URI'] }}', '#main-table');
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
        function getConsumptionByID(id) {
            var modal = $('#ModalConsumption');
            var modalHeader = modal.find('.modal-title');
            var modalBody = modal.find('.modal-body');
            $.ajax({
                url: '/consumption/'+id,
                success: function(data) {
                    modalHeader.html('');
                    modalBody.html('');
                    modalHeader.html($(data).filter('.title-page').val());
                    modalBody.html(data);
                    modal.modal({
                        show: true
                    });
                }
            });
        }
    </script>
@endsection