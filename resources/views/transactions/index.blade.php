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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h4>{{ __('common.filter') }}</h4>
                {{ Form::open(['url' => (isset($account) ? '/account/' . $account->id : '') . '/transactions/', 'method' => 'GET', 'class' => 'form-inline']) }}
                {{ Form::hidden('description', old('description')) }}
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::label('description', __('common.description')) }}
                            {{ Form::text('description', old('description'), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            {{ Form::label('date_init', __('common.date_init')) }}
                            {{ Form::date('date_init', old('date_init', $dateInit), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('date_end', __('common.date_end')) }}
                            {{ Form::date('date_end', old('date_end', $dateEnd), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                        </div>
                        <div class="col-md-4" style='text-align: center;'>
                            {{ Form::label('search', __('common.search')) }}
                            <button class="btn btn-info">
                                <i class="fa fa-search"></i> {{ __('common.search') }}
                            </button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="col-md-6">
                @if (isset($account) && $account->is_credit_card)
                    {{ Form::open(['url' => '/account/' . $account->id . '/transactions/', 'method' => 'GET', 'class' => 'form-inline']) }}
                    {{ Form::hidden('description', old('description')) }}
                    <h4>{{ __('common.filter') }}</h4>
                    <div class="container-fluid" style='margin-bottom:20px;'>
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::label('description', __('common.description')) }}
                                {{ Form::text('description', old('description'), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                {{ Form::label('date_init', __('transactions.invoice')) }}
                                {{ Form::select('invoice_id', $account->getOptionsInvoices(false), old('invoice_id', isset($request->invoice_id) ? $request->invoice_id : null), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                            </div>
                            <div class="col-md-4" style='text-align: center;'>
                                {{ Form::label('search', __('common.search')) }}
                                <button class="btn btn-info">
                                    <i class="fa fa-search"></i> {{ __('common.search') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                @endif
            </div>
        </div>
        <div class="row">
            <div class="container-fluid">
                <div class="row">
                    {{ Form::open(['url' => (isset($account) ? '/account/' . $account->id : '') . '/transactions/addCategories?' . $query, 'method' => 'PUT', 'class' => 'form-inline', 'style' => 'width:100%;']) }}
                    <div class="col-md-10">
                        <h4>{{ __('common.add_category') }}</h4>
                        {{ Form::text('categories', old('categories', ''), ['class' => 'form-control', 'data-role' => 'tagsinput']) }}
                    </div>
                    <div class="col-md-2" style="padding-top: 40px;">
                        {{ Form::button('<i class="fa fa-save"></i> '.__('common.submit'),['type'=>'submit', 'class'=>'btn btn-primary']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
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
        <tfoot>
        <tr>
            <td colspan="11">
                {{ $transactions->links('vendor.pagination.bootstrap-4') }}
            </td>
        </tr>
        </tfoot>
    </table>
@endsection
