<ul class="nav nav-tabs">
    @foreach ($years as $year)
        <li class="nav-item primary-color">
            <a class="nav-link {{ $actual->year == $year ? 'active' : '' }}"
               href="{{ url('accounts') }}?year={{ $year }}">{{ $year }}</a>
        </li>
    @endforeach
</ul>
<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped" style="margin-top:10px;">
        <thead>
        <tr class="title">
            <th colspan="5">{{ __('common.description') }}</th>
            @for ($month = 0; $month < 12; $month++)
                <th colspan="2" class="{{ $month == $actual->month ? 'table-active' : '' }}">
                    {{ __('common.months.' . $month) }} ({{ __('common.money_type') }})
                </th>
            @endfor
        </tr>
        </thead>
        <tbody>
        @foreach ($accounts as $account)
            <tr>
                <th class="title" rowspan="3">
                    {{ $account->description }}
                </th>
                <td class="title " rowspan="3">
                    <button class="btn btn-warning btn-iframe" title="{{ __('common.edit') }} {{ __('accounts.account') }}"
                       href="{{ url('accounts/' . $account->id . '/edit') }}">
                        <i class="fa fa-edit"></i>
                    </button>
                </td>
                <td class="title " rowspan="3">
                    {{ Form::open(['url' => '/accounts/' . $account->id . '', 'method' => 'DELETE']) }}
                    {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'style' => 'float:right;']) }}
                    {{ Form::close() }}
                </td>
                <td class="title " rowspan="3" {!! $account->is_credit_card ? '' : 'colspan=2' !!}>
                    <a class="btn btn-info" title="{{ __('common.import') }} {{ __('accounts.account') }}"
                       href="#" data-toggle="modal" data-target="#model_account_{{ $account->id }}">
                        <i class="fa fa-upload"></i>
                    </a>
                </td>
                @if ($account->is_credit_card)
                    <td class="title " rowspan="3">
                        <a class="btn btn-secondary"
                           title="{{ __('invoices.title') }} {{ __('accounts.account') }}"
                           href="{{ url('account/' . $account->id . '/invoices') }}">
                            <i class="fas fa-receipt"></i>
                        </a>
                    </td>
                @endif
                @for ($month = 0; $month < 12; $month++)
                    <td class="{{ $month == $actual->month ? 'table-active' : '' }}" rowspan="3">
                        @if (isset($account->invoices) && isset($account->invoices[$month]))
                            <a class="btn btn-primary" title="{{ __('transactions.title') }}"
                               href="{{ url('account/' . $account->id . '/transactions') }}?invoice_id={{ $account->invoices[$month]->id }}">
                                <i class="fa fa-list"></i>
                            </a>
                        @else
                            <a class="btn btn-primary" title="{{ __('transactions.title') }}"
                               href="{{ url('account/' . $account->id . '/transactions') }}?year={{$actual->year}}&month={{ $month+1 }}">
                                <i class="fa fa-list"></i>
                            </a>
                        @endif
                    </td>
                    <td class="{{ $month == $actual->month ? 'table-active' : '' }} text-right"
                        title="{{ __('accounts.totals_paid') }}">
                        {!! format_money($account->paidValues[$actual->year][$month]) !!}
                    </td>
                @endfor
            </tr>
            <tr>
                @for ($month = 0; $month < 12; $month++)
                    <td class="{{ $month == $actual->month ? 'table-active' : '' }} text-right"
                        title="{{ __('accounts.totals_not_paid') }}">
                        {!! format_money($account->notPaidValues[$actual->year][$month]) !!}
                    </td>
                @endfor
            </tr>
            <tr>
                @for ($month = 0; $month < 12; $month++)
                    <td class="{{ $month == $actual->month ? 'table-active' : '' }} text-right" style="font-weight: bold;"
                        title="{{ __('accounts.totals') }}">
                        {!! format_money($account->paidValues[$actual->year][$month] + $account->notPaidValues[$actual->year][$month]) !!}
                    </td>
                @endfor
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr class="title">
            <th colspan="5">
                {{ __('accounts.totals_paid') }}:
            </th>
            @for ($month = 0; $month < 12; $month++)
                <th class="{{ $month == $actual->month ? 'table-active' : '' }} text-right" colspan="2">
                    {!! format_money($sum->paid[$month]) !!}
                </th>
            @endfor
        </tr>
        <tr class="title">
            <th colspan="5">
                {{ __('accounts.totals_not_paid') }}:
            </th>
            @for ($month = 0; $month < 12; $month++)
                <th class="{{ $month == $actual->month ? 'table-active' : '' }} text-right" colspan="2">
                    {!! format_money($sum->notPaid[$month]) !!}
                </th>
            @endfor
        </tr>
        <tr class="title">
            <th colspan="5">
                {{ __('accounts.totals') }}:
            </th>
            @for ($month = 0; $month < 12; $month++)
                <th class="{{ $month == $actual->month ? 'table-active' : '' }} text-right" colspan="2">
                    {!! format_money($sum->notPaid[$month] + $sum->paid[$month]) !!}
                </th>
            @endfor
        </tr>
        </tfoot>
    </table>
</div>
