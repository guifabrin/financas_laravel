@extends('layouts.app')

@section('content')
<div class="container-fluid">
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
                <th>{{__('accounts.amount')}}</th>
                <th>{{__('accounts.end_month_amount')}}</th>
                <th>{{__('common.actions')}}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $month_sum = 0;
                $end_month_sum = 0;
              ?>
              @foreach($accounts as $account)
                <tr>
                  <td>
                    {{$account->id}}
                  </td>
                  <td>
                    {{$account->description}}
                  </td>
                  <td class="text-right">
                    <?php
                      $month_sum += $account->amount;
                    ?>
                    {!!format_money($account->amount)!!}
                  </td>
                  <td class="text-right">
                    <?php
                      $end_month = $account->amount+$account->transactions()->where('paid', false)->where('date','<',date('Y-m-t'))->sum('value');
                      $end_month_sum += $end_month;
                    ?>
                    {!!format_money($end_month)!!}
                  </td>
                  <td>
                    <a href="/accounts/{{$account->id}}/edit">{{__('common.edit')}}</a>
                    <a href="/accounts/{{$account->id}}/confirm">{{__('common.remove')}}</a>
                    <a href="/account/{{$account->id}}/transactions">{{__('transactions.title')}}</a>
                  </td>
                </tr>
                <?php 
                  foreach($account->creditCards() as $creditCard){
                    ?>
                      <tr>
                        <td style="border-top:none;">
                          {{$creditCard->id}}
                        </td>
                        <td style="border-top:none;">
                          {{$creditCard->description}}
                        </td>
                        <td style="border-top:none;">
                        </td>
                        <td style="border-top:none;" class="text-right">
                          {!!format_money($creditCard->amount)!!}
                        </td>
                        <td style="border-top:none;">
                          <a href="/accounts/{{$creditCard->id}}/edit">{{__('common.edit')}}</a>
                          <a href="/accounts/{{$creditCard->id}}/confirm">{{__('common.remove')}}</a>
                          <a href="/account/{{$creditCard->id}}/transactions">{{__('transactions.title')}}</a>
                        </td>
                      </tr>
                    <?php
                      $end_month_sum += $creditCard->amount;
                  }
                ?>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="2" class="text-right">
                  {{__('accounts.totals')}}
                </th>
                <th class="text-right">{!!format_money($month_sum)!!}</th>
                <th class="text-right">{!!format_money($end_month_sum)!!}</th>
                <th></th>
              </tr>
            </tfoot>
          </table>
          {{$accounts->links()}}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection