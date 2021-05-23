@extends('layouts.app')

@section('title')
 {{__('common.repeat')}} {{__('transactions.title')}} {{__('common.in')}} {{$account->id}}/{{$account->description}}
@endsection

@section('title-buttons')
  <a class="btn btn-secondary" href="{{url('account/'.$account->id.'/transactions')}}">
    <i class="fa fa-arrow-left"></i>
  </a>
@endsection

@section('content')
  {{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.$transaction->id.'/confirmRepeat'.( isset($_GET['date_init']) && isset($_GET['date_end']) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end']   : ''), 'method'=>'POST']) }}
    {{__('transactions.repeat_text', ['id'=>$transaction->id, 'description'=>$transaction->description, 'accountId'=>$account->id, 'accountDescription'=>$account->description])}} <input type="number" name="times" min="1" value="1" style="text-align: right;"> {{__('transactions.times')}}
    @include('shared.submit') 
  {{ Form::close() }} 
@endsection

@section('script')
  <script src="{{ asset('js/transactions/form.js') }}"></script>
@endsection