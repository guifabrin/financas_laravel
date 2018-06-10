@extends('layouts.app')

@section('title')
  {{__('transactions.confirm_destroy')}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="/account/{{$account->id}}/transactions">
    <i class="fa fa-arrow-left"></i>
  </a>
@endsection

@section('content')
{{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.$transaction->id, 'method'=>'DELETE']) }}
  {{__('transactions.confirmation_text', ['id'=>$transaction->id, 'description'=>$transaction->description, 'accountId'=>$account->id, 'accountDescription'=>$account->description])}}
  @include('shared.submit')
{{ Form::close() }} 
@endsection