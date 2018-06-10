@extends('layouts.app')

@section('title')
  {{__('invoices.confirm_destroy')}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="/account/{{$account->id}}/invoices">
    <i class="fa fa-arrow-left"></i>
  </a>
@endsection

@section('content')
 {{ Form::open(['url' => '/account/'.$account->id.'/invoice/'.$invoice->id, 'method'=>'DELETE']) }}
    {{__('invoices.confirmation_text', ['id'=>$invoice->id, 'description'=>$invoice->description])}}
    @include('shared.submit')
  {{ Form::close() }} 
@endsection