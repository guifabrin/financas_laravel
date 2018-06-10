@extends('layouts.app')

@section('title')
  {{$action}} {{__('invoices.title')}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="/account/{{$account->id}}/invoices">
    <i class="fa fa-arrow-left"></i>
  </a>
@endsection

@section('content')
  {{ Form::open(['url' => '/account/'.$account->id.'/invoice/'.( isset($invoice) ? $invoice->id : '' ), 'method' => isset($invoice) ? 'PUT' : 'POST' ]) }}
    <div class="col-md-12">
      <div class="form-group">
        {{ Form::label('description', __('common.description')) }}
        {{ Form::text('description', old('description', ( isset($invoice) ? $invoice->description : null )), ['class'=>'form-control']) }}
      </div>
      <div class="form-group">
        {{ Form::label('date_init', __('common.date_init')) }}
        {{ Form::input('dateTime-local', 'date_init', old('date_init', ( isset($invoice) ? $invoice->date_init : null )), ['class'=>'form-control']) }}
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        {{ Form::label('date_end', __('common.date_end')) }}
        {{ Form::input('dateTime-local', 'date_end', old('date_end', ( isset($invoice) ? $invoice->date_end : null )), ['class'=>'form-control']) }}
      </div>
      <div class="form-group">
        {{ Form::label('debit_date', __('common.debit_date')) }}
        {{ Form::input('dateTime-local', 'debit_date', old('debit_date', ( isset($invoice) ? $invoice->debit_date : null )), ['class'=>'form-control']) }}
      </div>
      @include('shared.submit')
    </div>
  {{ Form::close() }}
@endsection