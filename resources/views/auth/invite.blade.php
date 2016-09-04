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
            <form class="form-horizontal" id="inviteForm" role="form">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="email" class="col-md-4 control-label">@lang('invite.input_email')</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-md-4 control-label">@lang('invite.input_name')</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="button" id="submit-btn" class="btn btn-primary">
                            <i class="fa fa-btn fa-sign-in"></i> @lang('invite.submit')
                        </button>
                    </div>
                </div>
            </form>
            @endif
@endsection
@section('custom-scripts')
<script>
    $('#submit-btn').click(function() {
        var name = $('input#name').val();
        var email = $('input#email').val();
        if(email != '' && name != '') {
            $.ajax({
                url: '{{ url('/sendEmailToAdmin') }}',
                method: 'post',
                data: $('#inviteForm').serialize(),
                error: function() {
                    alert('@lang('invite.error')');
                },
                success: function(data) {
                    if(data == 'true') {
                        $('.modal-footer-invite')
                                .append
                                ('\
                                    <p class="notification-text text-success">\
                                        @lang('invite.user') <b>' + name + '</b> @lang('invite.success_register')\
                                    </p>\
                            ');
                    } else if(data == 'false') {
                        $('.modal-footer-invite')
                                .append
                                ('\
                                    <p class="notification-text text-info">\
                                        @lang('invite.user') @lang('invite.with_email'): <b>' + email + '</b> @lang('invite.already_exist')\
                                    </p>\
                            ');
                    } else {
                        $('.modal-footer-invite')
                                .append
                                ('\
                                    <p class="notification-text text-danger">\
                                        @lang('invite.error')\
                                    </p>\
                            ');
                    }
                    setTimeout(function() {
                        $('.notification-text').hide();
                    }, 2500);
                }
            });
        } else {
            if(email == '') {
                alert('@lang('invite.empty_email_error')');
            }
            else if(name == '') {
                alert('@lang('invite.empty_name_error')');
            }
        }
    });

</script>
@endsection
