@extends('layouts.app')
@section('title')
    {{ __('accounts.title') }}
@endsection
@section('title-buttons')
    <a class="btn btn-secondary" title="{{ __('common.view_mode') }}"
        href="{{ url('accounts') }}?view_mode={{ $modeView == 'table' ? 'card' : 'table' }}">
        <i class="fas fa-exchange-alt"></i> {{ __('common.view_mode') }}
    </a>
    <button class="btn btn-primary btn-iframe" title="{{ __('common.add') }}" href="{{ url('accounts/create') }}">
        <i class="fa fa-plus"></i> {{ __('common.add') }}
    </button>
@endsection
@section('content')
    @include('accounts/mode_view/'.$modeView)

    @foreach ($accounts as $account)
        @include('accounts/import', ['isAccount'=>true, 'id'=>$account->id])
    @endforeach
@endsection

@section('script')
    <script src="{{ asset('js/accounts/index.js') }}"></script>
    <script>
        window.syncAccount = (account_id, automated_body, input) => {
            let isafe = "";
            if (automated_body) {
                isafe = prompt("isafe");
                if (!isafe || isafe.length != 6) {
                    return;
                }
            }
            var icon = $(input).find('i')[0];
            if (icon.classList.contains('fa-spin')) {
                return;
            }
            icon.classList.add('fa-spin')
            const auth = btoa("{{ Auth::user()->email }}:{{ Auth::user()->password }}");
            const headers = new Headers();
            headers.append("Authorization", "Basic " + auth);
            fetch("http://dracma.ddns.net:8990/api/v1/automated/" + account_id, {
                    method: "POST",
                    headers: headers,
                    mode: "cors",
                    body: isafe,
                }).then(() => {
                    icon.classList.remove('fa-spin')
                })
                .catch((ex) => {
                    console.log("error", ex);
                    icon.classList.remove('fa-spin')
                });
        }
    </script>
@endsection
