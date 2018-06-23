@extends('layouts.app')
@section('title')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-9">
        {{__('accounts.title')}}      
      </div>
    </div>
  </div>
@endsection
@section('title-buttons')
  <a class="btn btn-secondary" title="{{__('common.view_mode')}}" href="/accounts?view_mode={{$modeView=='table'?'card':'table'}}">
    <i class="fas fa-exchange-alt"></i> {{__('common.view_mode')}}
  </a>
  <a class="btn btn-primary" title="{{__('common.add')}}" href="/accounts/create">
    <i class="fa fa-plus"></i> {{__('common.add')}}
  </a>
@endsection
@section('content')
  <div class="container">
    <hr>
    <div class="row">
      <div class="col-md-4 text-center">
        <b>{{__('accounts.avg_max')}}</b>
        {!!format_money($avgMax)!!}
      </div>
      <div class="col-md-4 text-center">
        <b>{{__('accounts.avg_min')}}</b>
        {!!format_money($avgMin)!!}
      </div>
      <div class="col-md-4 text-center">
        <b>{{__('accounts.avg_avg')}}</b>
        {!!format_money($avgAvg)!!}
      </div>
    </div>
    <hr>
  </div>

  @include('accounts/mode_view/'.$modeView)

  @foreach($accounts as $account)
    @include('accounts/import', ['isAccount'=>true, 'id'=>$account->id])
  @endforeach
@endsection

@section('script')
  <script src="{{ asset('js/accounts/index.js') }}"></script>
@endsection