@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="container-fluid">
            <div class="col-md-11">
              {{__('transactions.title')}}
            </div>
            <div class="col-md-1 text-right">
              <a title="{{__('common.add')}}" href="/account/{{$account->id}}/transaction/create"><i class="fa fa-plus"></i></a>
            </div>
          </div>
        </div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          
          {{ Form::open(['url' => '/account/'.$account->id.'/transactions/', 'method'=>'GET', 'class'=>'form-inline']) }}
          <div class="container-fluid">
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
              <button class="btn btn-info"><i class="fa fa-search"></i></button>
            </div>
          </div>
          {{ Form::close() }}
          {{ Form::open(['url' => '/account/'.$account->id.'/transactions/', 'method'=>'GET', 'class'=>'form-inline']) }}
          <div class="container-fluid" style='margin-bottom:20px;'>
            <hr>
            <div class="col-md-10">
              {{ Form::label('date_init', __('transactions.invoice')) }}
              {{ Form::select('invoice_id', $account->getOptionsInvoices(false), old('invoice_id', isset($request->invoice_id) ? $request->invoice_id : null), ['class'=>'form-control', 'style'=>'width:100%;']) }}
            </div>
            <div class="col-md-2" style='text-align: center;'>
              {{ Form::label('search', __('common.search')) }}
              <button class="btn btn-info"><i class="fa fa-search"></i></button>
            </div>
          </div>
          {{ Form::close() }}
          
          <table class="table">
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
                  <td class="text-center">
                  @if (!$account->is_credit_card)
                   <div class="checkbox">
                        <label>
                          <input disabled="true" type="checkbox" {{$transaction->paid?"checked='true'":""}}/>
                        </label>
                      </div>
                    </td>
                  @endif
                  <td class="text-center" style="vertical-align: middle;">
                    <a title="{{__('common.edit')}} {{__('transactions.transaction')}}" href="/account/{{$account->id}}/transaction/{{$transaction->id}}/edit{{ (isset($_GET['date_init']) && isset($_GET['date_end'])) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '' }}"><i class="fa fa-pencil"/></i></a>
                    <a title="{{__('common.remove')}} {{__('transactions.transaction')}}" href="/account/{{$account->id}}/transaction/{{$transaction->id}}/confirm{{isset($_GET['date_init']) && isset($_GET['date_end']) ?'?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '' }}"><i class="fa fa-trash"/></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection