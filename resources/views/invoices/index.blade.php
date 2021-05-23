@extends('layouts.app')

@section('title')
  {{__('invoices.title')}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="{{url('accounts')}}">
    <i class="fa fa-arrow-left"></i> {{__('common.back')}}
  </a>
  <a class="btn btn-primary" title="{{__('common.add')}}" href="{{url('account/'.$account->id.'/invoice/create')}}">
    <i class="fa fa-plus"></i> {{__('common.add')}}
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
      <th colspan="3">{{__('common.actions')}}</th>
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
          <a class="btn btn-info" title="{{__('common.import')}} {{__('accounts.account')}}" href="#" data-toggle="modal" data-target="#model_account_{{$invoice->id}}">
            <i class="fa fa-upload"/></i> {{__('common.import')}}
          </a>
        </td>
        <td>
           <a class="btn btn-warning" title="{{__('common.edit')}} {{__('invoices.invoice')}}" href="{{url('account/'.$account->id.'/invoice/'.$invoice->id.'/edit')}}">
            <i class="fa fa-edit"/></i> {{__('common.edit')}}
          </a>
        </td>
        <td>
          <a class="btn btn-danger" title="{{__('common.remove')}} {{__('invoices.invoice')}}" href="{{url('account/'.$account->id.'/invoice/'.$invoice->id.'/confirm')}}">
            <i class="fa fa-trash"/></i> {{__('common.remove')}}
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