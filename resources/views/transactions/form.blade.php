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
            {{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.(isset($transaction)?$transaction->id:'').( isset($_GET['date_init']) && isset($_GET['date_end']) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end']   : ''), 'method'=>(isset($transaction)?'PUT':'POST')]) }}
              <div class="form-group">
                {{ Form::label('date', __('common.date')) }}
                {{ Form::date('date', old('date', (isset($transaction)?$transaction->date:null)), ['class'=>'form-control']) }}
              </div>
              @if ($account->is_credit_card)
                <div class="form-group">
                  {{ Form::label('invoice_id', __('transactions.invoice')) }}
                  {{ Form::select('invoice_id', $account->getOptionsInvoices(), old('invoice_id', isset($transaction) ? $transaction->invoice_id : null), ['class'=>'form-control']) }}
                </div>
                <div id="new_invoice" class="form-group" style="{{ isset($transaction) && ($transaction->invoice_id==-1 || $transaction->invoice_id==null) ? '' : 'display: none' }};">
                  {{ Form::label('invoice_description', __('transactions.invoice_description')) }}
                  {{ Form::text('invoice_description', old('invoice_description', null), ['class'=>'form-control']) }}
                  {{ Form::label('invoice_date_init', __('transactions.invoice_date_init')) }}
                  {{ Form::date('invoice_date_init', old('invoice_date_init', null), ['class'=>'form-control']) }}
                  {{ Form::label('invoice_date_end', __('transactions.invoice_date_end')) }}
                  {{ Form::date('invoice_date_end', old('invoice_date_end', null), ['class'=>'form-control']) }}
                  {{ Form::label('invoice_debit_date', __('transactions.invoice_debit_date')) }}
                  {{ Form::date('invoice_debit_date', old('invoice_debit_date', null), ['class'=>'form-control']) }}
                </div>
              @endif
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

@section('script')
  <script src="{{ asset('js/transactions/form.js') }}"></script>
@endsection