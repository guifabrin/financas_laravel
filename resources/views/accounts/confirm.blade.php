@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          {{__('accounts.confirm_destroy')}}
          <a href="/accounts">{{__('common.back')}}</a>
        </div>

        <div class="panel-body">
         {{ Form::open(['url' => '/accounts/'.$account->id, 'method'=>'DELETE']) }}
            {{__('accounts.confirmation_text', ['id'=>$account->id, 'description'=>$account->description])}}
            <div class="form-group">
              <a href="/accounts">{{__('common.back')}}</a>
              {{ Form::submit(__('common.save'),['class'=>'btn']) }}
            </div>
          {{ Form::close() }} 
        </div>
      </div>
    </div>
  </div>
</div>
@endsection