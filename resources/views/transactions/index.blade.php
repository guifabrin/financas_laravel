@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          {{__('transactions.title')}}
          <a href="/account/{{$account->id}}/transaction/create">{{__('common.add')}}</a>
          {!!format_money($account->amount)!!}
        </div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          
          {{ Form::open(['url' => '/account/'.$account->id.'/transactions/', 'method'=>'GET', 'class'=>'form-inline']) }}
            <div class="form-group">
              {{ Form::label('date_init', __('common.date_init')) }}
              {{ Form::date('date_init', old('date_init', date('Y-m-01')), ['class'=>'form-control']) }}
            </div>
            <div class="form-group">
              {{ Form::label('date_end', __('common.date_end')) }}
              {{ Form::date('date_end', old('date_end', date('Y-m-t')), ['class'=>'form-control']) }}
            </div>
            <div class="form-group">
              {{ Form::submit(__('common.search'),['class'=>'btn']) }}
            </div>
          {{ Form::close() }}
          
          <table class="table">
            <thead>
              <tr>
                <th>{{__('common.id')}}</th>
                <th>{{__('common.date')}}</th>
                <th>{{__('invoice.description')}}</th>
                <th>{{__('common.description')}}</th>
                <th>{{__('transactions.value')}}</th>
                <th>{{__('transactions.paid')}}</th>
                <th>{{__('common.actions')}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($transactions as $transaction)
                <tr>
                  <td>
                    {{$transaction->id}}
                  </td>
                  <td>
                    {{format_date($transaction->date)}}
                  </td>
                  <td>
                    {{$transaction->invoice != null ? $transaction->invoice->description : '' }}
                  </td>
                  <td>
                    {{$transaction->description}}
                  </td>
                  <td>
                    {!!format_money($transaction->value)!!}
                  </td>
                  <td>
                    <div class="checkbox">
                      <label>
                        <input disabled="true" type="checkbox" {{$transaction->paid?"checked='true'":""}}/>
                      </label>
                    </div>
                  </td>
                  <td>
                    <a href="/account/{{$account->id}}/transaction/{{$transaction->id}}/edit{{ (isset($_GET['date_init']) && isset($_GET['date_end'])) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '' }}">{{__('common.edit')}}</a>
                    <a href="/account/{{$account->id}}/transaction/{{$transaction->id}}/confirm{{isset($_GET['date_init']) && isset($_GET['date_end']) ?'?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '' }}">{{__('common.remove')}}</a>
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