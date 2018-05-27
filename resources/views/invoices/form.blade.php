@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          {{$action}}
          {{__('invoices.title')}}
          <a href="/account/{{$account->id}}/invoices">{{__('common.back')}}</a>
        </div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          {{ Form::open(['url' => '/account/'.$account->id.'/invoice/'.( isset($invoice) ? $invoice->id : '' ), 'method' => isset($invoice) ? 'PUT' : 'POST' ]) }}
            <div class="col-md-6">
              <div class="form-group">
                {{ Form::label('description', __('common.description')) }}
                {{ Form::text('description', old('description', ( isset($invoice) ? $invoice->description : null )), ['class'=>'form-control']) }}
              </div>
              <div class="form-group">
                {{ Form::label('date_init', __('common.date_init')) }}
                {{ Form::input('dateTime-local', 'date_init', old('date_init', ( isset($invoice) ? $invoice->date_init : null )), ['class'=>'form-control']) }}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {{ Form::label('date_end', __('common.date_end')) }}
                {{ Form::input('dateTime-local', 'date_end', old('date_end', ( isset($invoice) ? $invoice->date_end : null )), ['class'=>'form-control']) }}
              </div>
              <div class="form-group">
                {{ Form::label('debit_date', __('common.debit_date')) }}
                {{ Form::input('dateTime-local', 'debit_date', old('debit_date', ( isset($invoice) ? $invoice->debit_date : null )), ['class'=>'form-control']) }}
              </div>
              <div class="form-group">
                {{ Form::submit(__('common.save'),['class'=>'btn']) }}
              </div>
            </div>
          {{ Form::close() }} 
        </div>
      </div>
    </div>
  </div>
</div>
@endsection