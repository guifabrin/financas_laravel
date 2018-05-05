@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="container-fluid">
            <div class="col-md-11">
              {{__('accounts.title')}}
            </div>
            <div class="col-md-1 text-right">
              <a title="{{__('common.add')}}" href="/accounts/create"><i class="fa fa-plus"></i></a>
            </div>
          </div>
        </div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          <ul class="nav nav-tabs">
            <?php
              $yearDiff = (date('Y')-$year);
              $j = 10-$yearDiff;
              if ($j<=0){
                $j=1;
              }
            ?>
            @for ($i=$year-$j; $i<=$year; $i++)
              <li {!!$i==$year?'class="active"':''!!}>
                <a href="/accounts?year={{$i}}">{{$i}}</a>
              </li>
            @endfor
            @if ($year<date('Y'))
              @for ($i=$year+1; $i<=date('Y'); $i++)
                <li>
                  <a href="/accounts?year={{$i}}">{{$i}}</a>
                </li>
              @endfor
            @endif
          </ul>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr class="active">
                  <th colspan="3">{{__('common.description')}}</th>
                  <?php for($i=0; $i<12; $i++) { ?>
                    <th colspan="2">
                      {{__('common.months.'.$i)}}   
                    </th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php
                  $monthValueAccount = [];
                  $monthValueAccountNotPaid = [];
                ?>
                @foreach($accounts as $account)
                  <tr>
                    <th class="active" rowspan="2">
                      {{$account->description}}
                    </th>
                    <th class="active text-right" rowspan="2" style="vertical-align: middle;">
                      <a title="{{__('common.edit')}} {{__('accounts.account')}}" href="/accounts/{{$account->id}}/edit"><i class="fa fa-pencil"/></i></a>
                    </th>
                    <th class="active text-right" rowspan="2" style="vertical-align: middle;">
                      <a title="{{__('common.remove')}} {{__('accounts.account')}}" href="/accounts/{{$account->id}}/confirm"><i class="fa fa-trash"/></i></a>
                    </th>
                    <?php 
                      $monthValueAccount[$account->id] = [];
                      for($i=0; $i<12; $i++) {
                        $date_init = date($year.'-'.($i+1).'-1');
                        $date_end = date('Y-m-t', strtotime($date_init));
                    ?>
                        <?php
                          $monthValueAccountNotPaid[$account->id][$i] = $account->transactions()->where('paid', false)->where('date','<=',$date_end)->sum('value'); 
                          $monthValueAccount[$account->id][$i] = $account->transactions()->where('paid', true)->where('date','<=',$date_end )->sum('value');
                        ?>
                        <td class="text-right" rowspan="2" style="vertical-align: middle;">
                          <a style="margin-right: 5px;" title="{{__('transactions.title')}}" href="/account/{{$account->id}}/transactions?date_init={{$date_init}}&date_end={{$date_end}}">
                            <i class="fa fa-list"></i>
                          </a>
                        </td>
                        <td class="text-right">
                          {!!format_money($monthValueAccount[$account->id][$i])!!}
                        </td>
                      <?php
                        } 
                      ?>
                  </tr>
                  <tr>
                    <?php for($i=0; $i<12; $i++) { ?>
                      <td class="text-right">
                        @if ($monthValueAccountNotPaid[$account->id][$i]>0)
                          {!!format_money($monthValueAccountNotPaid[$account->id][$i])!!}
                        @else
                          {!!format_money(0)!!}
                        @endif
                      <?php } ?>
                    </td>
                  </tr>
                  <?php

                    foreach($account->creditCards() as $creditCard){
                      $monthValueAccount[$creditCard->id] = [];
                      $monthValueAccountNotPaid[$creditCard->id] = [];
                      ?>
                        <tr>
                          <th class="active">
                            {{$creditCard->description}}
                          </th>
                          <th class="active text-right" style="vertical-align: middle;">
                            <a title="{{__('common.edit')}} {{__('accounts.account')}}" href="/accounts/{{$creditCard->id}}/edit"><i class="fa fa-pencil"/></i></a>
                          </th>
                          <th class="active text-right" style="vertical-align: middle;">
                            <a  title="{{__('common.remove')}} {{__('accounts.account')}}" href="/accounts/{{$creditCard->id}}/confirm"><i class="fa fa-trash"/></i></a>
                          </th>
                           @for($i=0; $i<12; $i++)
                              <?php
                                $date_init = date('Y-m-d', strtotime(date($year.'-'.($i+1).'-1')));
                                $date_end = date('Y-m-t', strtotime(date($year.'-'.($i+1).'-1')));
                                $invoice = $creditCard->invoices()->whereBetween('debit_date',[$date_init, $date_end])->first();
                              ?>
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
                                  <td class="text-right" style="vertical-align: middle;">
                                    <a style="margin-right: 5px;" title="{{__('transactions.title')}}" href="/account/{{$creditCard->id}}/transactions?invoice_id={{$invoice->id}}">
                                      <i class="fa fa-list"></i>
                                    </a>
                                  </td>
                                  <td class="text-right">
                                    <a href="#" onclick="show_invoice({{$invoice->id}})">{!!format_money($value)!!}</a>
                                    <div id="invoice_{{$invoice->id}}" style="display: none;">
                                      <label>Gasto:</label> {!!format_money($invoice->transactions()->where('value','<',0)->sum('value'))!!}<br>
                                      <label>Pago:</label> {!!format_money($invoice->transactions()->where('value','>',0)->sum('value'))!!}<br>
                                    </div>
                                  </td>
                                @else
                                  <td colspan="2" class="text-right">
                                    {!!format_money(0)!!}
                                  </td>
                                @endif
                              </td>
                          @endfor
                        </tr>
                      <?php
                    }
                  ?>
                @endforeach
              </tbody>
              <tfoot>
                <tr class="active">
                  <th class="text-right" colspan="3">
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
                    <th class="text-right" colspan="2">
                      {!!format_money($sumPaid[$i])!!}
                    </th>
                  <?php } ?>
                </tr>
                <tr class="active">
                  <th class="text-right" colspan="3">
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
                    <th class="text-right" colspan="2">
                      {!!format_money($sumNotPaid[$i])!!}
                    </th>
                  <?php } ?>
                </tr>
                <tr class="active">
                  <th class="text-right" colspan="3">
                    {{__('accounts.totals')}}
                  </th>
                  <?php for($i=0; $i<12; $i++) {
                    ?>
                    <th class="text-right" colspan="2">
                      {!!format_money($sumNotPaid[$i]+$sumPaid[$i])!!}
                    </th>
                  <?php } ?>
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