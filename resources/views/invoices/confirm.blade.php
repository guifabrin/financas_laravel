@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          {{__('accounts.confirm_destroy')}}
          <a href="/accounts/{{$account->id}}/invoices">{{__('common.back')}}</a>
        </div>

        <div class="panel-body">
         {{ Form::open(['url' => '/account/'.$account->id.'/invoice/'.$invoice->id, 'method'=>'DELETE']) }}
            {{__('invoices.confirmation_text', ['id'=>$invoice->id, 'description'=>$account->invoice])}}
            <div class="form-group">
              <a href="/accounts/{{$account->id}}/invoices">{{__('common.back')}}</a>
              {{ Form::submit(__('common.save'),['class'=>'btn']) }}
            </div>
          {{ Form::close() }} 
        </div>
      </div>
    </div>
  </div>
</div>
@endsection