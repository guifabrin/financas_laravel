@foreach($accounts as $account)
<div class="card col-md-3">
  <h5 class="card-title">{{$account->description}}</h5>
  <div class="card-body">
    <span>
      {{__('accounts.totals_paid')}}
      {{__('common.money_type')}} {!!format_money($monthValueAccount[$account->id][$actualMonth])!!}
    </span>
    <span>
      {{__('accounts.totals_not_paid')}}
      {{__('common.money_type')}} {!!format_money($monthValueAccountNotPaid[$account->id][$actualMonth])!!}
    </span>
    <span style="text-align: center">
      <a title="{{__('common.edit')}} {{__('accounts.account')}}" href="/accounts/{{$account->id}}/edit">
        <i class="fa fa-pencil"/></i>
      </a>
      <a title="{{__('common.remove')}} {{__('accounts.account')}}" href="/accounts/{{$account->id}}/confirm">
        <i class="fa fa-trash"/></i>
      </a>
      <a title="{{__('common.import')}} {{__('accounts.account')}}" href="#" data-toggle="modal" data-target="#model_account_{{$account->id}}">
        <i class="fa fa-upload"/></i>
      </a>
      @if ($account->is_credit_card)
        <a title="{{__('invoices.title')}} {{__('accounts.account')}}" href="/account/{{$account->id}}/invoices">
          <i class="fa fa-list"/></i>
        </a>
      @endif
      @if (isset($account->invoices) && isset($account->invoices[$actualMonth]))
        <a style="margin-right: 5px;" title="{{__('transactions.title')}}" href="/account/{{$account->id}}/transactions?invoice_id={{$account->invoices[$actualMonth]->id}}">
          <i class="fa fa-list"></i>
        </a>
      @else
        <a style="margin-right: 5px;" title="{{__('transactions.title')}}" href="/account/{{$account->id}}/transactions?date_init={{$dateInit[$actualMonth]}}&date_end={{$dateEnd[$actualMonth]}}">
          <i class="fa fa-list"></i>
        </a>
      @endif
    </span>
  </div>
</div>
@endforeach
<div class="card col-md-3">
  <h5 class="card-title">{{__('accounts.balance')}}</h5>
  <div class="card-body">
    <p class="card-text">
      <h6>{{__('accounts.totals_paid')}}</h5>
      {{__('common.money_type')}} {!!format_money($sumPaid[$actualMonth])!!}
      <h6>{{__('accounts.totals_not_paid')}}</h5>
      {{__('common.money_type')}} {!!format_money($sumNotPaid[$actualMonth])!!}
      <h6>{{__('accounts.totals')}}</h5>
      {{__('common.money_type')}} {!!format_money($sumNotPaid[$actualMonth]+$sumPaid[$actualMonth])!!}
    </p>
  </div>
</div>