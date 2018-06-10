@extends('layouts.app')

@section('title')
  {{__('invoices.title')}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="/accounts">
    <i class="fa fa-arrow-left"></i>
  </a>
  <a class="btn btn-secondary" title="{{__('common.add')}}" href="/account/{{$account->id}}/invoice/create">
    <i class="fa fa-plus"></i>
  </a>
@endsection

@section('content')
<table class="table table-bordered">
  <thead>
    <tr class="active">
      <th>{{__('common.id')}}</th>
      <th>{{__('common.description')}}</th>
      <th>{{__('invoices.date_init')}}</th>
      <th>{{__('invoices.date_end')}}</th>
      <th>{{__('invoices.debit_date')}}</th>
      <th>{{__('common.actions')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($invoices as $invoice)
      <tr>
        <td>{{$invoice->id}}</td>
        <td>{{$invoice->description}}</td>
        <td>{{formatDateTime($invoice->date_init)}}</td>
        <td>{{formatDateTime($invoice->date_end)}}</td>
        <td>{{formatDateTime($invoice->debit_date)}}</td>
        <td>
          <a class="btn btn-secondary" title="{{__('common.import')}} {{__('accounts.account')}}" href="#" data-toggle="modal" data-target="#model_account_{{$invoice->id}}">
            <i class="fa fa-upload"/></i>
          </a>
           <a class="btn btn-secondary" title="{{__('common.edit')}} {{__('invoices.invoice')}}" href="/account/{{$account->id}}/invoice/{{$invoice->id}}/edit">
            <i class="fa fa-edit"/></i>
          </a>
          <a class="btn btn-secondary" title="{{__('common.remove')}} {{__('invoices.invoice')}}" href="/account/{{$account->id}}/invoice/{{$invoice->id}}/confirm">
            <i class="fa fa-trash"/></i>
          </a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@foreach($invoices as $invoice)
  @include('accounts/import', ['isAccount'=>false, 'accountId'=>$account->id,'id'=>$invoice->id])
@endforeach
@endsection