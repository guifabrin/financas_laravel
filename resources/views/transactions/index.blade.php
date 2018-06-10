@extends('layouts.app')

@section('title')
  {{__('transactions.title')}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="/accounts">
    <i class="fa fa-arrow-left"></i>
  </a>
  <a class="btn btn-secondary" title="{{__('common.add')}}" href="/account/{{$account->id}}/transaction/create">
    <i class="fa fa-plus"></i>
  </a>
@endsection

@section('content')
  {{ Form::open(['url' => '/account/'.$account->id.'/transactions/', 'method'=>'GET', 'class'=>'form-inline']) }}
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-5">
          {{ Form::label('date_init', __('common.date_init')) }}
          {{ Form::date('date_init', old('date_init', date('Y-m-01')), ['class'=>'form-control', 'style'=>'width:100%;']) }}
        </div>
        <div class="col-md-5">
          {{ Form::label('date_end', __('common.date_end')) }}
          {{ Form::date('date_end', old('date_end', date('Y-m-t')), ['class'=>'form-control', 'style'=>'width:100%;']) }}
        </div>
        <div class="col-md-2" style='text-align: center;'>
          {{ Form::label('search', __('common.search')) }}
          <button class="btn btn-info">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </div>
  {{ Form::close() }}
  @if ($account->is_credit_card)
    <hr>
    {{ Form::open(['url' => '/account/'.$account->id.'/transactions/', 'method'=>'GET', 'class'=>'form-inline']) }}
      <div class="container-fluid" style='margin-bottom:20px;'>
        <div class="row">
          <div class="col-md-10">
            {{ Form::label('date_init', __('transactions.invoice')) }}
            {{ Form::select('invoice_id', $account->getOptionsInvoices(false), old('invoice_id', isset($request->invoice_id) ? $request->invoice_id : null), ['class'=>'form-control', 'style'=>'width:100%;']) }}
          </div>
          <div class="col-md-2" style='text-align: center;'>
            {{ Form::label('search', __('common.search')) }}
            <button class="btn btn-info">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    {{ Form::close() }}
  @endif
          
  <table class="table" style="margin-top:10px;">
    <thead>
      <tr>
        <th>{{__('common.id')}}</th>
        <th>{{__('common.date')}}</th>
        @if ($account->is_credit_card)
          <th>{{__('transactions.invoice')}}</th>
        @endif
        <th>{{__('common.description')}}</th>
        <th class="text-center">{{__('transactions.value')}}</th>
        @if (!$account->is_credit_card)
        <th class="text-center">{{__('transactions.paid')}}</th>
        @endif
        <th class="text-center">{{__('common.actions')}}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($transactions as $transaction)
        <tr>
          <td>
            {{$transaction->id}}
          </td>
          <td>
            {{formatDate($transaction->date)}}
          </td>
          @if ($account->is_credit_card)
            <td>
              {{$transaction->invoice != null ? $transaction->invoice->description : '' }}
            </td>
          @endif
          <td>
            {{$transaction->description}}
          </td>
          <td class="text-right">
            {!!format_money($transaction->value)!!}
          </td>
          @if (!$account->is_credit_card)
            <td class="text-center">
             <div class="checkbox">
                <label style="margin-bottom: 0px;">
                  <input style="vertical-align: middle;" disabled="true" type="checkbox" {{$transaction->paid?"checked='true'":""}}/>
                </label>
              </div>
            </td>
          @endif
          <td class="text-center">
            <a class="btn btn-secondary" title="{{__('common.repeat')}} {{__('transactions.transaction')}}" href="/account/{{$account->id}}/transaction/{{$transaction->id}}/repeat{{ (isset($_GET['date_init']) && isset($_GET['date_end'])) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '' }}">
              <i class="fas fa-redo-alt"/></i>
            </a>
            <a class="btn btn-secondary" title="{{__('common.edit')}} {{__('transactions.transaction')}}" href="/account/{{$account->id}}/transaction/{{$transaction->id}}/edit{{ (isset($_GET['date_init']) && isset($_GET['date_end'])) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '' }}">
              <i class="fa fa-edit"/></i>
            </a>
            <a class="btn btn-secondary" title="{{__('common.remove')}} {{__('transactions.transaction')}}" href="/account/{{$account->id}}/transaction/{{$transaction->id}}/confirm{{isset($_GET['date_init']) && isset($_GET['date_end']) ?'?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '' }}">
              <i class="fa fa-trash"/></i>
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection