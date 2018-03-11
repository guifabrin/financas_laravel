@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Accounts</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ Form::open(array('url' => '/accounts')) }}
                      {{ Form::label('description', 'description') }}
                      {{ Form::text('description') }}
                      {{ Form::label('is_credit_card', 'is_credit_card') }}
                      {{ Form::checkbox('is_credit_card') }}
                      {{ Form::label('prefer_debit_account_id', 'debit account') }}
                      {{ Form::select('prefer_debit_account_id', $selectAccounts) }}
                      {{ Form::label('debit_day', 'debit date') }}
                      {{ Form::number('debit_day') }}
                      {{ Form::label('credit_close_day', 'credit_close day') }}
                      {{ Form::number('credit_close_day') }}
                      {{ Form::submit() }}
                    {{ Form::close() }} 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection