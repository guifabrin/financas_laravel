@extends('layouts.app')

@section('title')
    {{__('login.register')}}
@endsection

@section('content')
    <form role="form" method="POST" action="{{ url('/register') }}">
        {!! csrf_field() !!}

        <div class="form-group row">
            <label class="col-lg-6 col-form-label text-lg-right">{{__('login.name')}}</label>

            <div class="col-lg-4">
                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                       value="{{ old('name') }}" required>
                @if ($errors->has('name'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-6 col-form-label text-lg-right">{{__('login.email')}}</label>

            <div class="col-lg-4">
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                       value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-6 col-form-label text-lg-right">{{__('login.password')}}</label>

            <div class="col-lg-4">
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                       name="password" required>
                @if ($errors->has('password'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-6 col-form-label text-lg-right">{{__('login.password_confirmation')}}</label>

            <div class="col-lg-4">
                <input type="password"
                       class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                       name="password_confirmation" required>
                @if ($errors->has('password_confirmation'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-4 offset-lg-6">
                <button type="submit" class="btn btn-primary">
                    {{__('login.register')}}
                </button>
            </div>
        </div>
    </form>
@endsection
