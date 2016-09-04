@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
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
                                @if(!isset($checked))
                                <form class="form-horizontal" id="inviteForm" role="form">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                    <div class="form-group">
                                        <label for="text" class="col-md-4 control-label">
                                            @lang('raisingStatus.write_to_admin'):
                                        </label>

                                        <div class="col-md-6">
                                            <textarea id="text" name="text"
                                                      placeholder="@lang('raisingStatus.placeholder_text')"
                                            class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="button" id="submit-btn" class="btn btn-primary">
                                                <i class="fa fa-btn fa-sign-in"></i>
                                                @lang('raisingStatus.submit')
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                @else
                                    @if($checked == 'true')
                                        <h4 class="text-success">
                                            @lang('raisingStatus.success')
                                        </h4>
                                    @elseif($checked == 'false')
                                        <h4 class="text-danger">
                                            @lang('raisingStatus.failed')
                                        </h4>
                                    @endif
                                @endif
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-scripts')
    <script>
        $('#submit-btn').click(function() {
            var text = $('#text').val();
            if(text != '') {
                $.ajax({
                    url: '{{ url('/sendEmailRaisingStatus') }}',
                    method: 'post',
                    data: $('#inviteForm').serialize(),
                    error: function() {
                        alert('@lang('raisingStatus.error')');
                    },
                    success: function() {
                        $('.panel')
                                .append
                                ('\
                                <div class="panel-footer">\
                                    <p class="text-success">\
                                        @lang('raisingStatus.submit_ok') \
                                    </p>\
                                </div>\
                            ');
                        setTimeout(function() {
                            $('.panel-footer').hide();
                        }, 2500);
                    }
                });
            } else {
                alert('@lang('raisingStatus.no_text')');
            }
        });
    </script>
@endsection
