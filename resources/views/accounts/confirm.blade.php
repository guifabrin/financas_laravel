@extends('layouts.app')

@section('title')
  {{__('accounts.confirm_destroy')}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="/accounts">
    <i class="fa fa-arrow-left"></i>
  </a>
@endsection

@section('content')
 {{ Form::open(['url' => '/accounts/'.$account->id.'', 'method'=>'DELETE']) }}
    {{__('accounts.confirmation_text', ['id'=>$account->id, 'description'=>$account->description])}}
  @include('shared.submit')
  {{ Form::close() }}
@endsection