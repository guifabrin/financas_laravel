@extends('layouts.app')

@section('title')
    {{__('login.reset_password')}}
@endsection

@section('content')
  <form role="form" method="POST" action="{{ url('/password/reset') }}">
    {!! csrf_field() !!}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group row">
      <label class="col-lg-4 col-form-label text-lg-right">{{__('login.email')}}</label>

      <div class="col-lg-6">
        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email or old('email') }}"
        >
        @if ($errors->has('email'))
          <div class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
          </div>
        @endif
      </div>
    </div>

    <div class="form-group row">
      <label class="col-lg-4 col-form-label text-lg-right">Password</label>

      <div class="col-lg-6">
        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
        >
        @if ($errors->has('password'))
          <div class="invalid-feedback">
            <strong>{{ $errors->first('password') }}</strong>
          </div>
        @endif
      </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-4 col-form-label text-lg-right">{{__('login.password_confirmation')}}</label>
        <div class="col-lg-6">
          <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation">
          @if ($errors->has('password_confirmation'))
            <div class="invalid-feedback">
              <strong>{{ $errors->first('password_confirmation') }}</strong>
            </div>
          @endif
        </div>
    </div>

    <div class="form-group row">
      <div class="col-lg-6 offset-lg-4">
        <button type="submit" class="btn btn-primary">
          {{__('login.reset_password')}}
        </button>
      </div>
    </div>
  </form>
@endsection
