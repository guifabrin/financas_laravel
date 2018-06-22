@extends('layouts.app')
@section('title')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-9">
        {{__('accounts.title')}}      
      </div>
      <div class="col-md-1">
        <label style="font-size: 10px;">{{__('accounts.avg_max')}}</label>
        {!!format_money($avgMax)!!}
      </div>
      <div class="col-md-1">
        <label style="font-size: 10px;">{{__('accounts.avg_min')}}</label>
        {!!format_money($avgMin)!!}
      </div>
      <div class="col-md-1">
        <label style="font-size: 10px;">{{__('accounts.avg_avg')}}</label>
        {!!format_money($avgAvg)!!}
      </div>
    </div>
  </div>
@endsection
@section('title-buttons')
  <a class="btn btn-secondary" title="{{__('common.view_mode')}}" href="/accounts?view_mode={{$modeView=='table'?'card':'table'}}">
    <i class="fas fa-exchange-alt"></i>
  </a>
  <a class="btn btn-secondary" title="{{__('common.add')}}" href="/accounts/create">
    <i class="fa fa-plus"></i>
  </a>
@endsection
@section('content')
  @include('accounts/mode_view/'.$modeView)

  @foreach($accounts as $account)
    @include('accounts/import', ['isAccount'=>true, 'id'=>$account->id])
  @endforeach
@endsection

@section('script')
  <script src="{{ asset('js/accounts/index.js') }}"></script>
@endsection