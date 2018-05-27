@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="container-fluid">
            <div class="col-md-11">
              {{__('accounts.title')}}
            </div>
            <div class="col-md-1 text-right">
              <a title="{{__('common.add')}}" href="/account/{{$account->id}}/invoice/create"><i class="fa fa-plus"></i></a>
            </div>
          </div>
        </div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
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
                     <a title="{{__('common.edit')}} {{__('invoices.invoice')}}" href="/account/{{$account->id}}/invoice/{{$invoice->id}}/edit"><i class="fa fa-pencil"/></i></a>
                    <a title="{{__('common.remove')}} {{__('invoices.invoice')}}" href="/account/{{$account->id}}/invoice/{{$invoice->id}}/confirm"><i class="fa fa-trash"/></i></a>
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