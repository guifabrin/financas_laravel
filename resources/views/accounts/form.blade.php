@extends('layouts.iframe')

@section('title')
    {{ isset($account) ? __('common.edit') : __('common.add') }} {{ __('accounts.title') }}
@endsection

@section('title-buttons')
    <a class="btn btn-secondary" href="{{ url('accounts') }}">
        <i class="fa fa-arrow-left"></i>
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            {{ Form::open(['url' => '/accounts' . (isset($account) ? '/' . $account->id : ''), 'method' => isset($account) ? 'PUT' : 'POST']) }}
            <div class="form-group">
                {{ Form::label('description', __('common.description')) }}
                {{ Form::text('description', old('description', isset($account) ? $account->description : null), ['class' => 'form-control']) }}
            </div>
            @if (!isset($account))
                <div class="form-group">
                    {{ Form::label('is_credit_card', __('accounts.is_credit_card')) }}
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('is_credit_card', 1, old('is_credit_card', false)) }}
                        </label>
                    </div>
                </div>
            @endif
            <div class="form-group">
                {{ Form::label('ignore', __('accounts.ignore')) }}
                <div class="checkbox">
                    <label>
                        {{ Form::checkbox('ignore', 1, old('ignore', isset($account) && $account->ignore == '1')) }}
                    </label>
                </div>
            </div>
            {{ Form::button('<i class="fa fa-save"></i> ' . __('common.submit'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
            {{ Form::close() }}
        </div>
    </div>
@endsection
