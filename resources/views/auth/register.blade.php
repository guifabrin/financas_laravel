@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div style="text-align: center;">
                <h2>
                    {{ __('login.register') }}
                    <br><br>
                </h2>
                <img src="{{ asset('images/icon.png') }}" style="width: 50%;" />
            </div>
            <form role="form" method="POST" action="{{ url('/register') }}">
                {!! csrf_field() !!}

                <div class="form-group">
                    <label class="col-form-label text-lg-right">{{ __('login.name') }}</label>

                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                        value="{{ old('name') }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="col-form-label text-lg-right">{{ __('login.email') }}</label>

                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                        value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="col-form-label text-lg-right">{{ __('login.password') }}</label>

                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                        name="password" required>
                    @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="col-form-label text-lg-right">{{ __('login.password_confirmation') }}</label>

                    <input type="password"
                        class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                        name="password_confirmation" required>
                    @if ($errors->has('password_confirmation'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('login.register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
