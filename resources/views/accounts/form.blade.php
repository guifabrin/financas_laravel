@extends('layouts.app')

@section('title')
  {{$action}} {{__('accounts.title')}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="{{url('accounts')}}">
    <i class="fa fa-arrow-left"></i>
  </a>
@endsection

@section('content')
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
    @include('shared.submit')
  {{ Form::close() }}
@endsection

@section('script')
  <script src="{{ asset('js/accounts/form.js') }}"></script>
@endsection