@extends('layouts.app')

@section('title')
    {{__('login.reset_password')}}
@endsection

@section('content')
    <form role="form" method="POST" action="{{ url('/password/email') }}">
        {!! csrf_field() !!}

        <div class="form-group row">
            <label class="col-lg-4 col-form-label text-lg-right">{{__('login.email')}}</label>

            <div class="col-lg-6">
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                       value="{{ old('email') }}">

                @if ($errors->has('email'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 offset-lg-4">
                <button type="submit" class="btn btn-primary">
                    {{__('login.send_email_reset')}}
                </button>
            </div>
        </div>
    </form>
@endsection
