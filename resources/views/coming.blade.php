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
                        <?php if($comings != '' && count($comings) != 0) { ?>
                            <div class="table-responsive">
                                <table border="1" id="main-table"
                                       class="table-bordered col-md-10 col-md-offset-1 text-center">
                                    <thead>
                                    <tr>
                                        <td class="col-md-3">
                                            @lang('coming.date')
                                        </td>
                                        <td class="col-md-3">
                                            @lang('coming.provider')
                                        </td>
                                        <td class="col-md-1">
                                            @lang('coming.count_position')
                                        </td>
                                        <td class="col-md-2">
                                            @lang('coming.total')
                                        </td>
                                        @if ( Auth::user()->status == 0)
                                            <td class="col-md-1"></td>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($comings as $product)
                                    <tr>
                                        <td>{{ date('y-m-d', strtotime($product->date)) }}</td>
                                        <td>
                                            <a class="link-button"
                                               onclick="getComingByID('{{ $product->id_of_coming }}')">
                                                {{ $product->provider }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $product->count }}
                                                @lang('coming.unit_count')
                                        </td>
                                        <td>
                                            {{ $product->total }}
                                                @lang('coming.unit_price')
                                        </td>

                                        @if ( Auth::user()->status == 0)
                                            <td>
                                                        <span onclick="removeComing('{{ $product->id_of_coming }}')"
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
                                    @lang('coming.sort_by_date')
                                </h3>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="from_date">
                                        @lang('coming.from'):
                                    </label>
                                    <div class="col-md-6">
                                        <input placeholder="ГГ-ММ-ДД"
                                               pattern="[0-9]+[0-9]+-[0-9]+[0-9]+-[0-3]+[0-9]" class="col-md-10"
                                               type="date" name="from_date" id="from_date">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" for="to_date">
                                        @lang('coming.to'):
                                    </label>
                                    <div class="col-md-6">
                                        <input placeholder="@lang('coming.placeholder_date')"
                                               pattern="[0-9]+[0-9]+-[0-9]+[0-9]+-[0-3]+[0-9]" class="col-md-10"
                                               type="date" name="to_date" id="to_date">
                                    </div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-md-6">
                                        <input type="submit" value="@lang('coming.submit_date_btn')"
                                               class="btn btn-default col-md-5">
                                        <button type="button"
                                                class="col-sm-offset-1 btn btn-default col-md-6" id="print">
                                            @lang('coming.print_table')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                        <form id="product-form" class="form-horizontal col-md-12" role="form">
                            <h3 class="col-md-offset-2">
                                @lang('coming.add_new_coming_title')
                            </h3>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="provider">
                                    @lang('coming.provider')
                                    <span style="color: red">*</span>:
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="col-sm-10" name="provider" id="provider"
                                           placeholder="@lang('coming.placeholder_provider')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="date_get">
                                    @lang('coming.label_date'):
                                </label>
                                <div class="col-sm-10">
                                    <input placeholder="@lang('coming.placeholder_date')"
                                           pattern="[0-9]+[0-9]+-[0-9]+[0-9]+-[0-3]+[0-9]" class="col-md-10"
                                           type="date" name="date_get" id="date_get">
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
                                <div class="col-sm-offset-1 col-sm-2">
                                    <input class="btn btn-default" type="button" onclick="addProduct()"
                                           value="@lang('coming.add_new_coming_btn')">
                                </div>
                                <?php if($_SERVER['REQUEST_URI'] === '/sortByDateComing') { ?>
                                <a href="{{ $url }}">
                                    <button type="button" class="col-sm-offset-1 col-md-4 btn btn-default">
                                        @lang('coming.back_now_mthfckr')
                                    </button>
                                </a>
                                <button type="button" class="col-sm-offset-1 btn btn-default col-md-3" id="print">
                                    @lang('coming.print_table')
                                </button>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                        <div id="ModalComing" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button"
                                                onclick="refreshTable('/coming', '#main-table')"
                                                class="close" data-dismiss="modal">
                                            &times;
                                        </button>
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
            if ($('input#provider').val() != '')
            {
                $.ajax({
                    url: '{{ $url }}',
                    type: 'post',
                    data: $('#product-form').serialize(),
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    '@lang('coming.title_of_modal')',
                                    '@lang('coming.success')',
                                    'success'
                            );
                            refreshTable('/coming', '#main-table');
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
                if($('input#provider').val() == '') {
                    customPopUpText(
                            '@lang('coming.title_of_modal')',
                            '@lang('coming.no_provider_error')',
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

        function removeComing(id) {
            if (id != '') {
                $.ajax({
                    url: '{{ $url_delete }}',
                    type: 'post',
                    data: 'coming_id='+id+'&_token={{ csrf_token() }}',
                    success: function (data) {
                        if (data == 'true') {
                            customPopUpText(
                                    '@lang('coming.title_of_modal')',
                                    '@lang('coming.success_delete')',
                                    'success'
                            );
                            refreshTable('/coming', '#main-table');
                        } else if (data == 'false') {
                            customPopUpText(
                                    '@lang('coming.title_of_modal')',
                                    '@lang('coming.error_deleting')',
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
            }
        }

        /**
         * Create and call modal window for coming products
         * @param id
         */

        function getComingByID(id) {
            var modal = $('#ModalComing');
            var modalHeader = modal.find('.modal-title');
            var modalBody = modal.find('.modal-body');
            $.ajax({
                url: '/coming/'+id,
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