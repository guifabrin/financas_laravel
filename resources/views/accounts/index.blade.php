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
@endsection
