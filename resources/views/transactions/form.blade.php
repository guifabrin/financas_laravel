@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                  {{$action}}
                  {{__('transactions.title')}} {{__('common.in')}} {{$account->id}}/{{$account->description}}
                  <a href="/accounts">{{__('common.back')}}</a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ Form::open(['url' => '/account/'.$account->id.(isset($transaction)?'/transaction/'.$transaction->id:''), 'method'=>(isset($transaction)?'PUT':'POST')]) }}
                      <div class="form-group">
                        {{ Form::label('date', __('common.date')) }}
                        {{ Form::date('date', old('date', (isset($transaction)?$transaction->date:null)), ['class'=>'form-control']) }}
                      </div>
                      <div class="form-group">
                        {{ Form::label('description', __('common.description')) }}
                        {{ Form::text('description', old('description', (isset($transaction)?$transaction->description:null)), ['class'=>'form-control']) }}
                      </div>
                      <div class="form-group">
                        {{ Form::label('value', __('transactions.value')) }}
                        {{ Form::number('value', old('value', (isset($transaction)?$transaction->value:null)), ['class'=>'form-control', 'step' => '0.01']) }}
                      </div>
                      <div class="form-group">
                        {{ Form::label('paid', __('transactions.paid')) }}
                        <div class="checkbox">
                          <label>
                            {{ Form::checkbox('paid', 1, old('paid', (isset($transaction)?$transaction->paid:false))) }}
                          </label>
                        </div>
                      </div>

                      <div class="form-group">
                        {{ Form::submit(__('common.save'),['class'=>'btn']) }}
                      </div>
                    {{ Form::close() }} 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection