<div class="row">
    <div class="col-md-9">
        <div class="container-fluid no-padding">
            <div class="row">
                @foreach ($accounts as $account)
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $account->description }}</h5>
                                @php
                                    $paid = $account->paidValues[$actual->year][$actual->month];
                                    $non = $account->notPaidValues[$actual->year][$actual->month];
                                    $total = $paid + $non;
                                @endphp
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6"><b>{{ __('accounts.totals_paid') }}:</b></div>
                                        <div class="col-md-6">{!! format_money($paid) !!}
                                        </div>
                                    </div>
                                </div>
                                @if ($non != 0)
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6"><b>{{ __('accounts.totals_not_paid') }}:</b></div>
                                            <div class="col-md-6">{!! format_money($non) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($total != $paid)
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6"><b>{{ __('accounts.totals') }}:</b></div>
                                            <div class="col-md-6">{!! format_money($total) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-warning"
                                   title="{{ __('common.edit') }} {{ __('accounts.account') }}"
                                   href="{{ url('accounts/' . $account->id . '/edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a class="btn btn-danger"
                                   title="{{ __('common.remove') }} {{ __('accounts.account') }}"
                                   href="{{ url('accounts/' . $account->id . '/confirm') }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <a class="btn btn-info"
                                   title="{{ __('common.import') }} {{ __('accounts.account') }}" href="#"
                                   data-toggle="modal" data-target="#model_account_{{ $account->id }}">
                                    <i class="fa fa-upload"></i>
                                </a>
                                @if (!(isset($account->invoices) && isset($account->invoices[$actual->month])))
                                    <a class="btn btn-secondary" style="margin-right: 5px;"
                                       title="{{ __('transactions.title') }}"
                                       href="{{ url('/account/' . $account->id . '/transactions') }}?month={{ $actual->month }}&year={{ $actual->year }}">
                                        <i class="fa fa-list"></i>
                                    </a>
                                @endif
                                @if ($account->is_credit_card)
                                    <a class="btn btn-secondary"
                                       title="{{ __('invoices.title') }} {{ __('accounts.account') }}"
                                       href="{{ url('account/' . $account->id . '/invoices') }}">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                @endif
                                @if ($account->automated_args)
                                    <button class="btn btn-primary"
                                            onclick="syncAccount({{ $account->id }}, {{ $account->automated_body == '1' ? 'true' : 'false' }})">
                                        <i class="fa fa-sync"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="container-fluid no-padding">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('accounts.balance') }}</h5>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6"><b>{{ __('accounts.totals_paid') }}</b></div>
                                    <div class="col-md-6">{!! format_money($sum->paid[$actual->month]) !!}</div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6"><b>{{ __('accounts.totals_not_paid') }}</b></div>
                                    <div class="col-md-6">{!! format_money($sum->notPaid[$actual->month]) !!}</div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6"><b>{{ __('accounts.totals') }}</b></div>
                                    <div class="col-md-6">{!! format_money($sum->notPaid[$actual->month] + $sum->paid[$actual->month]) !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
