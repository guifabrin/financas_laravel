@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          {{__('accounts.title')}}
          <a href="/accounts/create">{{__('common.add')}}</a>
        </div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          <table class="table">
            <thead>
              <tr>
                <th>{{__('common.id')}}</th>
                <th>{{__('common.description')}}</th>
                <th>{{__('accounts.is_credit_card')}}</th>
                <th>{{__('accounts.prefer_debit_account')}}</th>
                <th>{{__('accounts.debit_day')}}</th>
                <th>{{__('accounts.credit_close_day')}}</th>
                <th>{{__('accounts.amount')}}</th>
                <th>{{__('accounts.amount')}}</th>
                <th>{{__('common.actions')}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($accounts as $account)
                <tr>
                  <td>
                    {{$account->id}}
                  </td>
                  <td>
                    {{$account->description}}
                  </td>
                  <td>
                    <div class="checkbox">
                      <label><input disabled="true" type="checkbox" {{$account->is_credit_card?"checked='true'":""}}></label>
                    </div>
                  </td>
                  <td>
                    @if ($account->prefer_debit_account_id!=null)
                      {{$account->preferDebitAccount->description}}
                    @endif
                  </td>
                  <td>
                    {{$account->debit_day}}
                  </td>
                  <td>
                    {{$account->credit_close_day}}
                  </td>
                  <td>
                    {{$account->amount}}
                  </td>
                  <td>
                    {{$account->amount+$account->transactions()->where('paid', false)->sum('value')}}
                  </td>
                  <td>
                    <a href="/accounts/{{$account->id}}/edit">{{__('common.edit')}}</a>
                    <a href="/accounts/{{$account->id}}/confirm">{{__('common.remove')}}</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          {{$accounts->links()}}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection