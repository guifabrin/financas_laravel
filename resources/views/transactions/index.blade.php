@extends($request->iframe?'layouts.iframe':'layouts.app')

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
    @include('transactions.filters')
    <div class="row">
        <div class="col-12">
            <table class="table table-sm table-bordered table-striped" style="margin-top:10px;">
                <thead>
                    <tr>
                        <th>{{ __('common.date') }}</th>
                        @if (!isset($account) || $account->is_credit_card)
                            <th>{{ __('transactions.invoice') }}</th>
                        @endif
                        <th>{{ __('common.description') }}</th>
                        <th>{{ __('common.categories') }}</th>
                        <th class="text-center">{{ __('transactions.value') }}</th>
                        @if (!isset($account) || !$account->is_credit_card)
                            <th class="text-center">{{ __('transactions.paid') }}</th>
                        @endif
                        <th class="text-center">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>
                                {{ formatDate($transaction->date) }}
                            </td>
                            @if (!isset($account) || $account->is_credit_card)
                                <td>
                                    @if ($transaction->account->is_credit_card)
                                        {{ $transaction->invoice != null ? $transaction->invoice->description : '' }}
                                    @endif
                                </td>
                            @endif
                            <td>
                                {{ $transaction->description }}
                            </td>
                            <td>
                                @if (count($transaction->categories) > 0)
                                    @foreach ($transaction->categories as $category)
                                        @if ($category->category->description)
                                            <span
                                                class="badge badge bg-primary">{{ $category->category->description }}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-right">
                                {!! format_money($transaction->value) !!}
                            </td>
                            @if (!isset($account) || !$account->is_credit_card)
                                <td>
                                    <div class="checkbox">
                                        <label style="margin-bottom: 0px;">
                                            <input style="vertical-align: middle;" type="checkbox"
                                                {{ $transaction->paid ? "checked='true'" : '' }}
                                                onchange="payTransaction({{ $transaction->id }}, {{ !$transaction->paid }} )" />
                                        </label>
                                    </div>
                                </td>
                            @endif
                            <td class="text-center">
                                <div class="actions-buttons">
                                    <button class="btn btn-info btn-iframe"
                                        title="{{ __('common.repeat') }} {{ __('transactions.transaction') }}"
                                        href="{{ url('account/' . $transaction->account_id . '/transaction/' . $transaction->id . '/repeat') }}">
                                        <i class="fas fa-redo-alt"></i>
                                    </button>
                                    <button class="btn btn-warning btn-iframe"
                                        title="{{ __('common.edit') }} {{ __('transactions.transaction') }}"
                                        href="{{ url('account/' . $transaction->account_id . '/transaction/' . $transaction->id . '/edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    {{ Form::open(['url' => '/account/' . $transaction->account_id . '/transaction/' . $transaction->id, 'method' => 'DELETE', 'data-message' => __('transactions.confirmation_text', ['accountId' => $transaction->account->id, 'accountDescription' => $transaction->account->description, 'id' => $transaction->id, 'description' => $transaction->description])]) }}
                                    {{ Form::button('<i class="fa fa-trash"></i> ', ['type' => 'submit', 'class' => 'btn btn-danger', 'style' => 'float:right;']) }}
                                    {{ Form::close() }}
                                </div>
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
        </div>
    </div>
@endsection
@section('script')
    <script>
        const auth = btoa("{{ Auth::user()->email }}:{{ Auth::user()->password }}");
        const headers = new Headers();
        headers.append("Authorization", "Basic " + auth);
        window.payTransaction = (transaction_id, value) => {
            /*const self = this;
            fetch("http://localhost:8888/api/v1/transactions/" + transaction_id, {
                    method: "PUT",
                    headers: headers,
                    mode: "cors",
                    body: JSON.stringify({
                        paid: value ? 1 : 0
                    }),
                })
                .catch((ex) => {
                    console.log("error", ex);
                });*/
        }
    </script>
@endsection
