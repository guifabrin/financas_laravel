<ul class="nav nav-tabs">
  @foreach ($years as $year)
    <li class="nav-item primary-color">
      <a class="nav-link {{$actualYear==$year?'title':''}}" href="{{url('accounts')}}?year={{$year}}">{{$year}}</a>
    </li>
  @endforeach
</ul>
<div class="table-responsive">
  <table class="table table-sm table-bordered">
    <thead>
      <tr class="title">
        <th colspan="5">{{__('common.description')}}</th>
        @for ($i=0; $i<12; $i++)
          <th colspan="2" class="{{$i==$actualMonth?'actual':''}}">
            {{__('common.months.'.$i)}} ({{__('common.money_type')}})
          </th>
        @endfor
      </tr>
    </thead>
    <tbody>
      @foreach($accounts as $account)
        <tr>
          <th class="title" rowspan="3">
            {{$account->description}}
          </th>
          <td class="title " rowspan="3">
            <a class="btn btn-secondary" title="{{__('common.edit')}} {{__('accounts.account')}}" href="{{url('accounts/'.$account->id.'/edit')}}">
              <i class="fa fa-edit"/></i>
            </a>
          </td>
          <td class="title " rowspan="3">
            <a class="btn btn-secondary" title="{{__('common.remove')}} {{__('accounts.account')}}" href="{{url('accounts/'.$account->id.'/confirm')}}">
              <i class="fa fa-trash"/></i>
            </a>
          </td>
          <td class="title " rowspan="3" {!!$account->is_credit_card ? "" : "colspan=2" !!}>
            <a class="btn btn-secondary" title="{{__('common.import')}} {{__('accounts.account')}}" href="#" data-toggle="modal" data-target="#model_account_{{$account->id}}">
              <i class="fa fa-upload"/></i>
            </a>
          </td>
          @if ($account->is_credit_card)
            <td class="title " rowspan="3">
              <a class="btn btn-secondary" title="{{__('invoices.title')}} {{__('accounts.account')}}" href="{{url('account/'.$account->id.'/invoices')}}">
                <i class="fas fa-receipt"/></i>
              </a>
            </td>
          @endif
          @for($i=0; $i<12; $i++)
            <td class="{{$i==$actualMonth?'actual':''}}" rowspan="3">
              @if (isset($account->invoices) && isset($account->invoices[$i]))
                <a class="btn btn-secondary" title="{{__('transactions.title')}}" href="{{url('account/'.$account->id.'/transactions')}}?invoice_id={{$account->invoices[$i]->id}}">
                  <i class="fa fa-list"></i>
                </a>
              @else
                <a class="btn btn-secondary" title="{{__('transactions.title')}}" href="{{url('account/'.$account->id.'/transactions')}}?date_init={{$dateInit[$i]}}&date_end={{$dateEnd[$i]}}">
                  <i class="fa fa-list"></i>
                </a>
              @endif
            </td>
            <td class="{{$i==$actualMonth?'actual':''}} text-right" title="{{__('accounts.totals_paid')}}">
              {!!format_money($monthValueAccount[$account->id][$i])!!}
            </td>
          @endfor
        </tr>
        <tr>
          @for($i=0; $i<12; $i++)
            <td class="{{$i==$actualMonth?'actual':''}} text-right" title="{{__('accounts.totals_not_paid')}}">
              {!!format_money($monthValueAccountNotPaid[$account->id][$i])!!}
            </td>
          @endfor
        </tr>
        <tr>
          @for($i=0; $i<12; $i++)
            <td class="{{$i==$actualMonth?'actual':''}} text-right" style="font-weight: bold;" title="{{__('accounts.totals')}}">
              {!!format_money($monthValueAccount[$account->id][$i]+$monthValueAccountNotPaid[$account->id][$i])!!}
            </td>
          @endfor
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="title">
        <th class="" colspan="5">
          {{__('accounts.totals_paid')}}:
        </th>
        @for($i=0; $i<12; $i++)
          <th class="{{$i==$actualMonth?'actual':''}} text-right" colspan="2">
            {!!format_money($sumPaid[$i])!!}
          </th>
        @endfor
      </tr>
      <tr class="title">
        <th class="" colspan="5">
          {{__('accounts.totals_not_paid')}}:
        </th>
        @for($i=0; $i<12; $i++)
          <th class="{{$i==$actualMonth?'actual':''}} text-right" colspan="2">
            {!!format_money($sumNotPaid[$i])!!}
          </th>
        @endfor
      </tr>
      <tr class="title">
        <th class="" colspan="5">
          {{__('accounts.totals')}}:
        </th>
        @for($i=0; $i<12; $i++)
          <th class="{{$i==$actualMonth?'actual':''}} text-right" colspan="2">
            {!!format_money($sumNotPaid[$i]+$sumPaid[$i])!!}
          </th>
        @endfor
      </tr>
    </tfoot>
  </table>
</div>