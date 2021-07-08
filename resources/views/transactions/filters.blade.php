<div class="row">
    <div class="{{ isset($account) && $account->is_credit_card ? 'col-sm-6' : 'col-sm-12' }}">
        <h4>{{ __('common.filter') }}</h4>
        {{ Form::open(['url' => (isset($account) ? '/account/' . $account->id : '') . '/transactions/', 'method' => 'GET', 'class' => 'form-inline']) }}
        {{ Form::hidden('description', old('description')) }}
        <div class="container-fluid no-padding">
            <div class="row">
                <div class="col-sm-12">
                    {{ Form::label('description', __('common.description')) }}
                    {{ Form::text('description', old('description'), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    {{ Form::label('date_init', __('common.date_init')) }}
                    {{ Form::date('date_init', old('date_init', $dateInit), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                </div>
                <div class="col-sm-5">
                    {{ Form::label('date_end', __('common.date_end')) }}
                    {{ Form::date('date_end', old('date_end', $dateEnd), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                </div>
                <div class="col-sm-2" style='text-align: center;'>
                    <br>
                    <button class="btn btn-info">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
    @if (isset($account) && $account->is_credit_card)
        <div class="col-sm-6">
            {{ Form::open(['url' => '/account/' . $account->id . '/transactions/', 'method' => 'GET', 'class' => 'form-inline']) }}
            {{ Form::hidden('description', old('description')) }}
            <h4>{{ __('common.filter') }}</h4>
            <div class="container-fluid no-padding" style='margin-bottom:20px;'>
                <div class="row">
                    <div class="col-sm-12">
                        {{ Form::label('description', __('common.description')) }}
                        {{ Form::text('description', old('description'), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10">
                        {{ Form::label('date_init', __('transactions.invoice')) }}
                        {{ Form::select('invoice_id', $account->getOptionsInvoices(false), old('invoice_id', isset($request->invoice_id) ? $request->invoice_id : null), ['class' => 'form-control', 'style' => 'width:100%;']) }}
                    </div>
                    <div class="col-sm-2" style='text-align: center;'>
                        <br>
                        <button class="btn btn-info">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    @endif
</div>
<div class="row">
    <div class="col-sm-12">
        <h4>{{ __('common.add_category') }}</h4>

        {{ Form::open(['url' => (isset($account) ? '/account/' . $account->id : '') . '/transactions/addCategories', 'method' => 'PUT', 'style' => 'display: flex;']) }}
        {{ Form::text('categories', old('categories', ''), ['data-role' => 'tagsinput']) }}

        @foreach ($transactionsall as $transaction)
            {{ Form::hidden('id[]', $transaction->id) }}
        @endforeach
        <br>
        {{ Form::button('<i class="fa fa-save"></i> ', ['type' => 'submit', 'class' => 'btn btn-primary', 'style' => 'margin-left: 15px;']) }}
        {{ Form::close() }}
    </div>
</div>
