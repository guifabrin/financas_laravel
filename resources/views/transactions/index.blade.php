@extends('layouts.app')

@section('title')
    {{ __('transactions.title') }}
@endsection

@section('title-buttons')
    <a class="btn btn-secondary" href="{{ url('accounts') }}">
        <i class="fa fa-arrow-left"></i> {{ __('common.back') }}
    </a>
    @if (isset($account))
        <button class="btn btn-primary btn-iframe" title="{{ __('common.add') }}"
                href="{{ url('account/' . $account->id . '/transaction/create') }}">
            <i class="fa fa-plus"></i> {{ __('common.add') }}
        </button>
    @endif
@endsection

@section('content')
    <?php $query = (isset($_GET['description']) ? 'description=' . $_GET['description'] : '') . '&' .
        (isset($_GET['date_init']) && isset($_GET['date_end']) ? 'date_init=' . $_GET['date_init'] . '&date_end=' .
            $_GET['date_end'] : ''); ?>
    @include('transactions.filters')
    <table class="table table-sm table-bordered table-striped" style="margin-top:10px;">
        <thead>
        <tr>
            <th>{{ __('common.id') }}</th>
            <th>{{ __('common.date') }}</th>
            <th>{{ __('transactions.invoice') }}</th>
            <th>{{ __('common.description') }}</th>
            <th>{{ __('common.categories') }}</th>
            <th class="text-center">{{ __('transactions.value') }}</th>
            <th class="text-center">{{ __('transactions.paid') }}</th>
            <th class="text-center" colspan="3">{{ __('common.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>
                    {{ $transaction->id }}
                </td>
                <td>
                    {{ formatDate($transaction->date) }}
                </td>
                <td>
                    @if ($transaction->account->is_credit_card)
                        {{ $transaction->invoice != null ? $transaction->invoice->description : '' }}
                    @endif
                </td>
                <td>
                    {{ $transaction->description }}
                </td>
                <td>
                    @if (count($transaction->categories) > 0)
                        @foreach ($transaction->categories as $category)
                            @if ($category->category->description)
                                <span class="badge badge badge-info">{{ $category->category->description }}</span>
                            @endif
                        @endforeach
                    @endif
                </td>
                <td class="text-right">
                    {!! format_money($transaction->value) !!}
                </td>
                <td class="text-center">
                    @if (!$transaction->account->is_credit_card)
                        <div class="checkbox">
                            <label style="margin-bottom: 0px;">
                                <input style="vertical-align: middle;" disabled="true" type="checkbox"
                                        {{ $transaction->paid ? "checked='true'" : '' }} />
                            </label>
                        </div>
                    @endif
                </td>
                <td class="text-center">
                    <button class="btn btn-info btn-iframe"
                            title="{{ __('common.repeat') }} {{ __('transactions.transaction') }}"
                            href="{{ url('account/' . $transaction->account_id . '/transaction/' . $transaction->id . '/repeat') }}">
                        <i class="fas fa-redo-alt"></i> {{ __('common.repeat') }}
                    </button>
                </td>
                <td class="text-center">
                    <button class="btn btn-warning btn-iframe"
                            title="{{ __('common.edit') }} {{ __('transactions.transaction') }}"
                            href="{{ url('account/' . $transaction->account_id . '/transaction/' . $transaction->id . '/edit') }}">
                        <i class="fa fa-edit"></i> {{ __('common.edit') }}
                    </button>
                </td>
                <td class="text-center">
                    {{ Form::open(['url' => '/account/' . $transaction->account_id . '/transaction/' . $transaction->id, 'method' => 'DELETE']) }}
                    {{ Form::button('<i class="fa fa-trash"></i> ', ['type' => 'submit', 'class' => 'btn btn-danger', 'style' => 'float:right;']) }}
                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach
        </tbody>
        @if ($transactions->links('vendor.pagination.bootstrap-4')->toHtml())
            <tfoot>
            <tr>
                <td colspan="11">
                    {{ $transactions->links('vendor.pagination.bootstrap-4') }}
                </td>
            </tr>
            </tfoot>
        @endif
    </table>
@endsection
