@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
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
                <?php for($i=0; $i<12; $i++) { ?>
                  <th>
                    {{__('common.months.'.$i)}}
                    {{ date('Y-m-t', strtotime(date($year.'-'.($i+1).'-1'))) }}
                  </th>
                <?php } ?>
                <th>{{__('common.actions')}}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $monthValueAccount = [];
                $monthValueAccountNotPaid = [];
              ?>
              @foreach($accounts as $account)
                <tr>
                  <td>
                    {{$account->id}}
                  </td>
                  <td>
                    {{$account->description}}
                  </td>
                  <?php 
                    $monthValueAccount[$account->id] = [];
                    for($i=0; $i<12; $i++) {
                  ?>
                    <td class="text-right">
                      <?php
                        $monthValueAccountNotPaid[$account->id][$i] = $account->transactions()->where('paid', false)->where('date','<=', date('Y-m-t', strtotime(date($year.'-'.($i+1).'-1'))))->sum('value'); 
                        $monthValueAccount[$account->id][$i] = $account->transactions()->where('paid', true)->where('date','<=', date('Y-m-t', strtotime(date($year.'-'.($i+1).'-1'))))->sum('value');
                      ?>
                      {!!format_money($monthValueAccount[$account->id][$i])!!}
                      @if ($monthValueAccountNotPaid[$account->id][$i]>0)
                        {!!format_money($monthValueAccountNotPaid[$account->id][$i])!!}
                      @endif
                    <?php
                      } 
                    ?>
                  </td>
                  <td>
                    <a href="/accounts/{{$account->id}}/edit">{{__('common.edit')}}</a>
                    <a href="/accounts/{{$account->id}}/confirm">{{__('common.remove')}}</a>
                    <a href="/account/{{$account->id}}/transactions">{{__('transactions.title')}}</a>
                  </td>
                </tr>
                <?php

                  /*foreach($account->creditCards() as $creditCard){
                    ?>
                      <tr>
                        <td style="border-top:none;">
                          {{$creditCard->id}}
                        </td>
                        <td style="border-top:none;" colspan=11>
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
                      //$end_month_sum += $creditCard->amount;
                  }
                    */
                ?>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="2" class="text-right">
                  {{__('accounts.totals')}}
                </th>
                <?php for($i=0; $i<12; $i++) {
                  $sum = 0;
                  foreach ($accounts as $account) {
                    $sum += $monthValueAccount[$account->id][$i]; 
                  }
                  ?>
                  <th class="text-right">{!!format_money($sum)!!}</th>
                <?php } ?>
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