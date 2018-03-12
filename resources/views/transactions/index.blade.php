@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          {{__('transactions.title')}}
          <a href="/transactions/{{$account->id}}/create">{{__('common.add')}}</a>
        </div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          {!! Table::generateModel(
            [__('common.id'), __('common.date'), __('common.description'), __('transactions.value'), __('transactions.paid')], 
            $transactions,
            ['id', 'date', 'description', 'value', 'paid']) !!}
          {{ Table::links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection