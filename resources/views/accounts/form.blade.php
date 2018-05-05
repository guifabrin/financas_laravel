@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                  {{$action}}
                  {{__('accounts.title')}}
                  <a href="/accounts">{{__('common.back')}}</a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ Form::open(['url' => '/accounts'.(isset($account)?'/'.$account->id:''), 'method'=>(isset($account)?'PUT':'POST')]) }}
                      <div class="form-group">
                        {{ Form::label('description', __('common.description')) }}
                        {{ Form::text('description', old('description', (isset($account)?$account->description:null)), ['class'=>'form-control']) }}
                      </div>
                      <?php if (!isset($account)) { ?>
                        <div class="form-group">
                          {{ Form::label('is_credit_card', __('accounts.is_credit_card')) }}
                          <div class="checkbox">
                            <label>
                              {{ Form::checkbox('is_credit_card', 1, old('is_credit_card', (isset($account)?$account->is_credit_card:false))) }}
                            </label>
                          </div>
                        </div>
                      <?php } ?>
                      <?php if (!isset($account) || (isset($account) && $account->is_credit_card)) { ?>
                        <div class="form-group" style="{{ !isset($account)? 'display: none;' : '' }}">
                          {{ Form::label('prefer_debit_account_id', __('accounts.prefer_debit_account')) }}
                          {{ Form::select('prefer_debit_account_id', $selectAccounts, old('prefer_debit_account_id', (isset($account)?$account->prefer_debit_account_id:null)), ['class'=>'form-control']) }}
                        </div>
                      <?php } ?>
                      <div class="form-group">
                        {{ Form::submit(__('common.save'),['class'=>'btn']) }}
                      </div>
                    {{ Form::close() }} 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection