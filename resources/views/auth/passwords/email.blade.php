@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div style="text-align: center;">
                <h2>
                    {{ __('login.reset_password') }}
                    <br><br>
                </h2>
                <img src="{{ asset('images/icon.png') }}" style="width: 50%;" />
            </div>
            <form role="form" method="POST" action="{{ url('/password/email') }}">
                {!! csrf_field() !!}

                <div class="form-group row">
                    <label class="col-form-label text-lg-right">{{ __('login.email') }}</label>


                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                        value="{{ old('email') }}">

                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group row">
                    <button type="submit" class="btn btn-primary">
                        {{ __('login.send_email_reset') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
