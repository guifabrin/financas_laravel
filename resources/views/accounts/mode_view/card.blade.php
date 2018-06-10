<div class="row">
  <div class="col-md-9">
    <div class="container-fluid">
      <div class="row">
        @foreach($accounts as $account)
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{$account->description}}</h5>
                <p>
                  <span>{{__('accounts.totals_paid')}}: </span>
                  {{__('common.money_type')}} {!!format_money($monthValueAccount[$account->id][$actualMonth])!!}
                </p>
                <p>
                  <span>{{__('accounts.totals_not_paid')}}: </span>
                  {{__('common.money_type')}} {!!format_money($monthValueAccountNotPaid[$account->id][$actualMonth])!!}
                </p>
                <p>
                  <span>{{__('accounts.totals')}}: </span>
                  {{__('common.money_type')}} {!!format_money($monthValueAccount[$account->id][$actualMonth]+$monthValueAccountNotPaid[$account->id][$actualMonth])!!}
                </p>
              </div>
              <div class="card-footer">
                <a class="btn btn-secondary" title="{{__('common.edit')}} {{__('accounts.account')}}" href="/accounts/{{$account->id}}/edit">
                  <i class="fa fa-edit"/></i>
                </a>
                <a class="btn btn-secondary" title="{{__('common.remove')}} {{__('accounts.account')}}" href="/accounts/{{$account->id}}/confirm">
                  <i class="fa fa-trash"/></i>
                </a>
                <a class="btn btn-secondary" title="{{__('common.import')}} {{__('accounts.account')}}" href="#" data-toggle="modal" data-target="#model_account_{{$account->id}}">
                  <i class="fa fa-upload"/></i>
                </a>
                @if ($account->is_credit_card)
                  <a class="btn btn-secondary" title="{{__('invoices.title')}} {{__('accounts.account')}}" href="/account/{{$account->id}}/invoices">
                    <i class="fas fa-receipt"/></i>
                  </a>
                @endif
                @if (isset($account->invoices) && isset($account->invoices[$actualMonth]))
                  <a class="btn btn-secondary" style="margin-right: 5px;" title="{{__('transactions.title')}}" href="/account/{{$account->id}}/transactions?invoice_id={{$account->invoices[$actualMonth]->id}}">
                    <i class="fa fa-list"></i>
                  </a>
                @else
                  <a class="btn btn-secondary" style="margin-right: 5px;" title="{{__('transactions.title')}}" href="/account/{{$account->id}}/transactions?date_init={{$dateInit[$actualMonth]}}&date_end={{$dateEnd[$actualMonth]}}">
                    <i class="fa fa-list"></i>
                  </a>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{__('accounts.balance')}}</h5>
              <p>
                <span>{{__('accounts.totals_paid')}}</span>
                {{__('common.money_type')}} {!!format_money($sumPaid[$actualMonth])!!}
              </p>
              <p>
                <span>{{__('accounts.totals_not_paid')}}</span>
                {{__('common.money_type')}} {!!format_money($sumNotPaid[$actualMonth])!!}
              </p>
              <p>
                <span>{{__('accounts.totals')}}</span>
                {{__('common.money_type')}} {!!format_money($sumNotPaid[$actualMonth]+$sumPaid[$actualMonth])!!}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>