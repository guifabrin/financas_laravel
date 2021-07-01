@extends('layouts.iframe')

@section('title')
    {{ __('invoices.title') }}
@endsection

@section('title-buttons')
    <a class="btn btn-secondary" href="{{ url('accounts') }}">
        <i class="fa fa-arrow-left"></i> {{ __('common.back') }}
    </a>
    <button class="btn btn-primary btn-iframe" title="{{ __('common.add') }}"
        href="{{ url('account/' . $account->id . '/invoice/create') }}">
        <i class="fa fa-plus"></i> {{ __('common.add') }}
    </button>
@endsection

@section('content')
    <table class="table table-sm table-bordered table-striped" style="margin-top:10px;">
        <thead>
            <tr class="active">
                <th>{{ __('common.id') }}</th>
                <th>{{ __('common.description') }}</th>
                <th>{{ __('invoices.date_init') }}</th>
                <th>{{ __('invoices.date_end') }}</th>
                <th>{{ __('invoices.debit_date') }}</th>
                <th>{{ __('common.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->description }}</td>
                    <td>{{ formatDateTime($invoice->date_init) }}</td>
                    <td>{{ formatDateTime($invoice->date_end) }}</td>
                    <td>{{ formatDateTime($invoice->debit_date) }}</td>
                    <td class="actions-buttons">
                        <button class="btn btn-primary btn-iframe"
                            title="{{ __('common.add') }} {{ __('transactions.transaction') }}"
                            href="{{ url('account/' . $account->id . '/transaction/create?invoice_id=' . $invoice->id) }}">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button class="btn btn-info btn-iframe"
                            title="{{ __('common.import') }} {{ __('accounts.account') }}" href="#"
                            data-toggle="modal" data-target="#model_account_{{ $invoice->id }}">
                            <i class="fa fa-upload"></i>
                        </button>
                        <button class="btn btn-warning btn-iframe"
                            title="{{ __('common.edit') }} {{ __('invoices.invoice') }}"
                            href="{{ url('account/' . $account->id . '/invoice/' . $invoice->id . '/edit') }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        {{ Form::open(['url' => '/account/' . $account->id . '/invoice/' . $invoice->id, 'method' => 'DELETE', 'data-message' => __('invoices.confirmation_text', ['id' => $invoice->id, 'description' => $invoice->description])]) }}
                        {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger']) }}
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @foreach ($invoices as $invoice)
        @include('accounts/import', ['isAccount'=>false, 'accountId'=>$account->id,'id'=>$invoice->id])
    @endforeach
@endsection
