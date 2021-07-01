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
            <th>{{ __('common.description') }}</th>
            @for ($month = 0; $month < 12; $month++)
                <th class="{{ $month == $actual->month ? 'table-active' : '' }}">
                    {{ __('common.months.' . $month) }} ({{ __('common.money_type') }})
                </th>
            @endfor
        </tr>
        </thead>
        <tbody>
        @foreach ($accounts as $account)
            <tr style="{{ $account->ignore ? 'opacity:0.7' : '' }}">
                <th class="title">
                    {{ $account->id }}/{{ $account->description }}
                    <div class="actions-buttons">
                        @if ($account->automated_args)
                            <button class="btn btn-primary"
                                    onclick="syncAccount({{ $account->id }}, {{ $account->automated_body == '1' ? 'true' : 'false' }})">
                                <i class="fa fa-sync"></i>
                            </button>
                        @endif
                        <button class="btn btn-warning btn-iframe"
                                title="{{ __('common.edit') }} {{ __('accounts.account') }}"
                                href="{{ url('accounts/' . $account->id . '/edit') }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        {{ Form::open(['url' => '/accounts/' . $account->id . '', 'method' => 'DELETE', 'style' => 'float:right;', 'data-message' => __('accounts.confirmation_text', ['id' => $account->id, 'description' => $account->description])]) }}
                        {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger']) }}
                        {{ Form::close() }}
                        <a class="btn btn-info" title="{{ __('common.import') }} {{ __('accounts.account') }}"
                           href="#" data-toggle="modal" data-target="#model_account_{{ $account->id }}">
                            <i class="fa fa-upload"></i>
                        </a>
                        @if ($account->is_credit_card)
                            <button class="btn btn-secondary btn-iframe"
                                    title="{{ __('invoices.title') }} {{ __('accounts.account') }}"
                                    href="{{ url('account/' . $account->id . '/invoices') }}">
                                <i class="fas fa-receipt"></i>
                            </button>
                            <button class="btn btn-secondary btn-iframe"
                                    title="{{ __('common.add') }} {{ __('invoices.invoice') }}"
                                    href="{{ url('account/' . $account->id . '/invoice/create') }}">
                                <i class="fa fa-plus"></i>
                            </button>
                        @else
                            <button class="btn btn-primary btn-iframe"
                                    title="{{ __('common.add') }} {{ __('transactions.transaction') }}"
                                    href="{{ url('account/' . $account->id . '/transaction/create') }}">
                                <i class="fa fa-plus"></i>
                            </button>
                        @endif
                    </div>
                </th>
                @for ($month = 0; $month < 12; $month++)
                    <td class="{{ $month == $actual->month ? 'table-active' : '' }}">
                        @if ($account->is_credit_card)
                            @php
                                $invoices = $account->invoicesIn($actual->year, $month);
                            @endphp
                            @if ($invoices->count())
                                @foreach ($invoices as $invoice)
                                    <button class="btn btn-link btn-iframe"
                                            title="{{ __('transactions.title') }}"
                                            href="{{ url('account/' . $account->id . '/transactions') }}?invoice_id={{ $invoice->id }}">
                                        {!! format_money($invoice->total()) !!}
                                    </button>
                                @endforeach
                            @endif
                        @else
                            @php
                                $transactions = $account->transactionsIn($actual->year, $month);
                            @endphp
                            @if ($transactions->count())
                                <button class="btn btn-link btn-iframe" title="{{ __('transactions.title') }}"
                                        href="{{ url('account/' . $account->id . '/transactions?year=' . $actual->year . '&month=' . ($month + 1)) }}">
                                    @php
                                        $paid = $account->paidValues[$actual->year][$month];
                                        $non = $account->notPaidValues[$actual->year][$month];
                                        $total = $paid + $non;
                                    @endphp
                                    {!! format_money($paid) !!}
                                    @if ($non != 0)
                                        {!! format_money($non) !!}
                                    @endif
                                    @if ($total != $paid)
                                        {!! format_money($total) !!}
                                    @endif
                                </button>
                            @endif

                            <button class="btn btn-primary btn-iframe"
                                    title="{{ __('common.add') }} {{ __('transactions.transaction') }}"
                                    href="{{ url('account/' . $account->id . '/transaction/create?year=' . $actual->year . '&month=' . ($month + 1)) }}">
                                <i class="fa fa-plus"></i>
                            </button>
                        @endif
                    </td>
                @endfor
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr class="title">
            <th>
                {{ __('accounts.totals_paid') }}:
            </th>
            @for ($month = 0; $month < 12; $month++)
                <th class="{{ $month == $actual->month ? 'table-active' : '' }} text-right">
                    {!! format_money($sum->paid[$month]) !!}
                </th>
            @endfor
        </tr>
        <tr class="title">
            <th>
                {{ __('accounts.totals_not_paid') }}:
            </th>
            @for ($month = 0; $month < 12; $month++)
                <th class="{{ $month == $actual->month ? 'table-active' : '' }} text-right">
                    {!! format_money($sum->notPaid[$month]) !!}
                </th>
            @endfor
        </tr>
        <tr class="title">
            <th>
                {{ __('accounts.totals') }}:
            </th>
            @for ($month = 0; $month < 12; $month++)
                <th class="{{ $month == $actual->month ? 'table-active' : '' }} text-right">
                    {!! format_money($sum->notPaid[$month] + $sum->paid[$month]) !!}
                </th>
            @endfor
        </tr>
        </tfoot>
    </table>
</div>
