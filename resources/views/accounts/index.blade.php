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
          <div class="table-responsive">
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

                    foreach($account->creditCards() as $creditCard){
                      $monthValueAccount[$creditCard->id] = [];
                      $monthValueAccountNotPaid[$creditCard->id] = [];
                      ?>
                        <tr>
                          <td style="border-top:none;">
                            {{$creditCard->id}}
                          </td>
                          <td style="border-top:none;">
                            {{$creditCard->description}}
                          </td>
                           @for($i=0; $i<12; $i++)
                              <?php
                                $date_init = date('Y-m-d', strtotime(date($year.'-'.($i+1).'-1')));
                                $date_end = date('Y-m-t', strtotime(date($year.'-'.($i+1).'-1')));
                                $invoice = $creditCard->invoices()->whereBetween('debit_date',[$date_init, $date_end])->first();
                              ?>
                                <td style="border-top:none; width:160px;" class="text-right">
                                  @if (isset($invoice))
                                    <?php
                                      $value = $invoice->transactions()->sum('value');
                                      $lastInvoices = $creditCard->invoices()->where('id','<',$invoice->id)->get();
                                      foreach ($lastInvoices as $lastInvoice){
                                        $value+=$lastInvoice->transactions()->sum('value');
                                      }
                                      if ($creditCard->closed && $creditCard->debit_date>=date()){
                                        $monthValueAccount[$creditCard->id][$i] = $value; 
                                      } else {
                                        $monthValueAccountNotPaid[$creditCard->id][$i] = $value; 
                                      }
                                    ?>
                                    <button class="btn" style="width:100px; display: inline-block;" onclick="show_invoice({{$invoice->id}})">{!!format_money($value)!!}</button>
                                    <button class="btn" style="display: inline-block;" href="/account/{{$creditCard->id}}/transactions?invoice_id={{$invoice->id}}">
                                      <i class="fa fa-eye"></i>
                                    </button>
                                    <div id="invoice_{{$invoice->id}}" style="display: none;">
                                      <label>Gasto:</label> {!!format_money($invoice->transactions()->where('value','<',0)->sum('value'))!!}<br>
                                      <label>Pago:</label> {!!format_money($invoice->transactions()->where('value','>',0)->sum('value'))!!}<br>
                                    </div>
                                  @else
                                    {!!format_money(0)!!}
                                  @endif
                                </td>
                            @endfor
                          <td style="border-top:none;">
                            <a href="/accounts/{{$creditCard->id}}/edit">{{__('common.edit')}}</a>
                            <a href="/accounts/{{$creditCard->id}}/confirm">{{__('common.remove')}}</a>
                            <a href="/account/{{$creditCard->id}}/transactions">{{__('transactions.title')}}</a>
                          </td>
                        </tr>
                      <?php
                    }
                  ?>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="2" class="text-right">
                    {{__('accounts.totals_paid')}}
                  </th>
                  <?php
                    $sumPaid = [];
                   for($i=0; $i<12; $i++) {
                    $sumPaid[$i] = 0;
                    foreach ($accounts as $account) {
                      $sumPaid[$i] += $monthValueAccount[$account->id][$i]; 
                      foreach ($account->creditCards() as $creditCard) {
                        if (isset($monthValueAccount[$creditCard->id][$i])){
                          $sumPaid[$i] += $monthValueAccount[$creditCard->id][$i]; 
                        }
                      }
                    }?>
                    <th class="text-right">
                      {!!format_money($sumPaid[$i])!!}
                    </th>
                  <?php } ?>
                  <th></th>
                </tr>
                <tr>
                  <th colspan="2" class="text-right">
                    {{__('accounts.totals_not_paid')}}
                  </th>
                  <?php
                  $sumNotPaid = [];
                  for($i=0; $i<12; $i++) {
                    $sumNotPaid[$i] = 0;
                    foreach ($accounts as $account) {
                      $sumNotPaid[$i] += $monthValueAccountNotPaid[$account->id][$i]; 
                      foreach ($account->creditCards() as $creditCard) {
                        if (isset($monthValueAccountNotPaid[$creditCard->id][$i])){
                          $sumNotPaid[$i] += $monthValueAccountNotPaid[$creditCard->id][$i]; 
                        }
                      }
                    }
                    ?>
                    <th class="text-right">
                      {!!format_money($sumNotPaid[$i])!!}
                    </th>
                  <?php } ?>
                  <th></th>
                </tr> <tr>
                  <th colspan="2" class="text-right">
                    {{__('accounts.totals')}}
                  </th>
                  <?php for($i=0; $i<12; $i++) {
                    ?>
                    <th class="text-right">
                      {!!format_money($sumNotPaid[$i]+$sumPaid[$i])!!}
                    </th>
                  <?php } ?>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
          {{$accounts->links()}}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script src="{{ asset('js/accounts/index.js') }}"></script>
@endsection