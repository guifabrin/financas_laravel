@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">{{__('accounts.title')}} <a href="/accounts/create">{{__('common.add')}}</a></div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          {!! Table::generateModel(
            [__('common.id'), __('common.description'), __('accounts.is_credit_card'), __('accounts.prefer_debit_account'), __('accounts.debit_day'), __('accounts.credit_close_day')], 
            $accounts,
            ['id', 'description', 'is_credit_card', 'prefer_debit_account_id:preferDebitAccount:description', 'debit_day', 'credit_close_day']) !!}
          {{ Table::links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection