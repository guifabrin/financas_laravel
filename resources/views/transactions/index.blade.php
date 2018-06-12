@extends('layouts.app')

@section('title')
  {{__('transactions.title')}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="/accounts">
    <i class="fa fa-arrow-left"></i>
  </a>
  @if (isset($account))
    <a class="btn btn-secondary" title="{{__('common.add')}}" href="/account/{{$account->id}}/transaction/create">
      <i class="fa fa-plus"></i>
    </a>
  @endif
@endsection

@section('content')
  <?php
  $query = (isset($_GET['description']) ?'description='.$_GET['description'] : '').'&'.((isset($_GET['date_init']) && isset($_GET['date_end'])) ? 'date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '');
  ?>
  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
          {{ Form::label('description', __('common.description')) }}
          {{ Form::text('description', old('description'), ['class'=>'form-control', 'style'=>'width:100%;']) }}
        </div>
    </div>
  </div>
  {{ Form::open(['url' => (isset($account) ? '/account/'. $account->id : '' ) . '/transactions/', 'method'=>'GET', 'class'=>'form-inline']) }}
    {{ Form::hidden('description', old('description')) }}
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-5">
          {{ Form::label('date_init', __('common.date_init')) }}
          {{ Form::date('date_init', old('date_init'), ['class'=>'form-control', 'style'=>'width:100%;']) }}
        </div>
        <div class="col-md-5">
          {{ Form::label('date_end', __('common.date_end')) }}
          {{ Form::date('date_end', old('date_end'), ['class'=>'form-control', 'style'=>'width:100%;']) }}
        </div>
        <div class="col-md-2" style='text-align: center;'>
          {{ Form::label('search', __('common.search')) }}
          <button class="btn btn-info">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </div>
  {{ Form::close() }}
  @if (isset($account) && $account->is_credit_card)
    <hr>
    {{ Form::open(['url' => '/account/'.$account->id.'/transactions/', 'method'=>'GET', 'class'=>'form-inline']) }}
      {{ Form::hidden('description', old('description')) }}
      <div class="container-fluid" style='margin-bottom:20px;'>
        <div class="row">
          <div class="col-md-10">
            {{ Form::label('date_init', __('transactions.invoice')) }}
            {{ Form::select('invoice_id', $account->getOptionsInvoices(false), old('invoice_id', isset($request->invoice_id) ? $request->invoice_id : null), ['class'=>'form-control', 'style'=>'width:100%;']) }}
          </div>
          <div class="col-md-2" style='text-align: center;'>
            {{ Form::label('search', __('common.search')) }}
            <button class="btn btn-info">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    {{ Form::close() }}
  @endif
          
  <table class="table" style="margin-top:10px;">
    <thead>
      <tr>
        <th>{{__('common.id')}}</th>
        <th>{{__('common.date')}}</th>
        <th>{{__('transactions.invoice')}}</th>
        <th>{{__('common.description')}}</th>
        <th>{{__('common.categories')}}</th>
        <th class="text-center">{{__('transactions.value')}}</th>
        <th class="text-center">{{__('transactions.paid')}}</th>
        <th class="text-center">{{__('common.actions')}}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($transactions as $transaction)
        <tr>
          <td>
            {{$transaction->id}}
          </td>
          <td>
            {{formatDate($transaction->date)}}
          </td>
          <td>
            @if ($transaction->account->is_credit_card)
              {{$transaction->invoice != null ? $transaction->invoice->description : '' }}
            @endif
          </td>
          <td>
            {{$transaction->description}}
          </td>
          <td>
            @if (count($transaction->categories)>0)
              <div class="bootstrap-tagsinput">
                @foreach ($transaction->categories as $category)
                  <span class="badge badge badge-info">{{$category->category->description}}</span>
                @endforeach
              </div>
            @endif
          </td>
          <td class="text-right">
            {!!format_money($transaction->value)!!}
          </td>
          <td class="text-center">
            @if (!$transaction->account->is_credit_card)
             <div class="checkbox">
                <label style="margin-bottom: 0px;">
                  <input style="vertical-align: middle;" disabled="true" type="checkbox" {{$transaction->paid?"checked='true'":""}}/>
                </label>
              </div>
            @endif
          </td>
          <td class="text-center">
            <a class="btn btn-secondary" title="{{__('common.repeat')}} {{__('transactions.transaction')}}" href="/account/{{$transaction->account_id}}/transaction/{{$transaction->id}}/repeat?{{ $query }}">
              <i class="fas fa-redo-alt"/></i>
            </a>
            <a class="btn btn-secondary" title="{{__('common.edit')}} {{__('transactions.transaction')}}" href="/account/{{$transaction->account_id}}/transaction/{{$transaction->id}}/edit?{{ $query }}">
              <i class="fa fa-edit"/></i>
            </a>
            <a class="btn btn-secondary" title="{{__('common.remove')}} {{__('transactions.transaction')}}" href="/account/{{$transaction->account_id}}/transaction/{{$transaction->id}}/confirm?{{ $query }}">
              <i class="fa fa-trash"/></i>
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan=8 >
          {{$transactions->links('vendor.pagination.bootstrap-4')}}
        </td>
      </tr>
      <tr>
        <td colspan=8 >
          {{ Form::open(['url' => (isset($account) ? '/account/'. $account->id : '' ) . '/transactions/addCategories?'.$query, 'method'=>'PUT', 'class'=>'form-inline']) }}
            <div class="col-md-11">
              {{ Form::text('categories', old('categories', ''), ['class'=>'form-control', 'data-role'=>'tagsinput']) }}
            </div>
            <div class="col-md-1">
              @include('shared.submit')
            </div>
          {{ Form::close() }}
        </td>
      </tr>
    </tfoot>
  </table>
@endsection

@section('script')
<script>
  $('input[type=text][name=description]').bind("keyup", function(){
    $('input[type=hidden][name=description]').val(this.value);
  });
</script>
@endsection