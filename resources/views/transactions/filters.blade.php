        <div class="row">
            <div class="col-md-6">
                <h4>{{ __('common.filter') }}</h4>
                {{ Form::open(['url' => (isset($account) ? '/account/' . $account->id : '') . '/transactions/', 'method' => 'GET', 'class' => 'form-inline']) }}
                {{ Form::hidden('description', old('description')) }}
                <div class="container-fluid no-padding">
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
                    <div class="container-fluid no-padding" style='margin-bottom:20px;'>
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
                        {{ Form::button('<i class="fa fa-save"></i> ' . __('common.submit'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
