@extends('layouts.app')

@section('title')
    {{__('login.login')}}
@endsection

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="form-group row">
            <label for="email" class="col-lg-6 col-form-label text-lg-right">{{__('login.email')}}</label>

            <div class="col-lg-4">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ old('email') }}" required autofocus
                >
                @if ($errors->has('email'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-lg-6 col-form-label text-lg-right">{{__('login.password')}}</label>

            <div class="col-lg-4">
                <input id="password" type="password"
                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required
                >
                @if ($errors->has('password'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4 offset-lg-6">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input"
                               name="remember" {{ old('remember') ? 'checked' : '' }}> {{__('login.remember_me')}}
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4 offset-lg-6">
                <button type="submit" class="btn btn-primary">
                    {{__('login.login')}}
                </button>

                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{__('login.forgot')}}
                </a>
            </div>
        </div>
    </form>
@endsection
