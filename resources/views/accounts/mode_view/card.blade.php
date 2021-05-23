<div class="row">
  <div class="col-md-9">
    <div class="container-fluid">
      <div class="row">
        @foreach($accounts as $account)
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{$account->description}}</h5>
                <div class="container">
                  <div class="row">
                    <div class="col-md-6"><b>{{__('accounts.totals_paid')}}:</b></div>
                    <div class="col-md-6">{{__('common.money_type')}} {!!format_money($monthValueAccount[$account->id][$actualMonth])!!}</div>
                  </div>
                </div>
                <div class="container">
                  <div class="row">
                    <div class="col-md-6"><b>{{__('accounts.totals_not_paid')}}:</b></div>
                    <div class="col-md-6">{{__('common.money_type')}} {!!format_money($monthValueAccountNotPaid[$account->id][$actualMonth])!!}</div>
                  </div>
                </div>
                <div class="container">
                  <div class="row">
                    <div class="col-md-6"><b>{{__('accounts.totals')}}:</b></div>
                    <div class="col-md-6">{{__('common.money_type')}} {!!format_money($monthValueAccount[$account->id][$actualMonth]+$monthValueAccountNotPaid[$account->id][$actualMonth])!!}</div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <a class="btn btn-warning" title="{{__('common.edit')}} {{__('accounts.account')}}" href="{{url('accounts/'.$account->id.'/edit')}}">
                  <i class="fa fa-edit"></i> {{__('common.edit')}}
                </a>
                <a class="btn btn-danger" title="{{__('common.remove')}} {{__('accounts.account')}}" href="{{url('accounts/'.$account->id.'/confirm')}}">
                  <i class="fa fa-trash"></i> {{__('common.remove')}}
                </a>
                <a class="btn btn-info" title="{{__('common.import')}} {{__('accounts.account')}}" href="#" data-toggle="modal" data-target="#model_account_{{$account->id}}">
                  <i class="fa fa-upload"></i> {{__('common.import')}}
                </a>
                @if (!(isset($account->invoices) && isset($account->invoices[$actualMonth])))
                  <a class="btn btn-secondary" style="margin-right: 5px;" title="{{__('transactions.title')}}" href="{{url('/account/'.$account->id.'/transactions')}}?date_init={{$dateInit[$actualMonth]}}&date_end={{$dateEnd[$actualMonth]}}">
                    <i class="fa fa-list"></i> {{__('transactions.title')}}
                  </a>
                @endif

                @if ($account->is_credit_card)
                <a class="btn btn-secondary" title="{{__('invoices.title')}} {{__('accounts.account')}}" href="{{url('account/'.$account->id.'/invoices')}}">
                  <i class="fas fa-receipt"></i> {{__('invoices.title')}}
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
              <div class="container">
                <div class="row">
                  <div class="col-md-6"><b>{{__('accounts.totals_paid')}}</b></div>
                  <div class="col-md-6">{{__('common.money_type')}} {!!format_money($sumPaid[$actualMonth])!!}</div>
                </div>
              </div>
              <div class="container">
                <div class="row">
                  <div class="col-md-6"><b>{{__('accounts.totals_not_paid')}}</b></div>
                  <div class="col-md-6">{{__('common.money_type')}} {!!format_money($sumNotPaid[$actualMonth])!!}</div>
                </div>
              </div>
              <div class="container">
                <div class="row">
                  <div class="col-md-6"><b>{{__('accounts.totals')}}</b></div>
                  <div class="col-md-6">{{__('common.money_type')}} {!!format_money($sumNotPaid[$actualMonth]+$sumPaid[$actualMonth])!!}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>