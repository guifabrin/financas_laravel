@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="container-fluid">
            <div class="col-md-11">
              {{__('accounts.title')}}
            </div>
            <div class="col-md-1 text-right">
              <a title="{{__('common.view_mode')}}" href="/accounts?view_mode={{$modeView=='table'?'card':'table'}}"><i class="fa fa-list"></i></a>
              <a title="{{__('common.add')}}" href="/accounts/create"><i class="fa fa-plus"></i></a>
            </div>
          </div>
        </div>

        <div class="panel-body">
          @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
          @endif
          @include('accounts/mode_view/'.$modeView)
        </div>
      </div>
    </div>
  </div>
</div>

@foreach($accounts as $account)
  @include('accounts/import', ['account'=>$account])
@endforeach
@endsection

@section('script')
  <script src="{{ asset('js/accounts/index.js') }}"></script>
@endsection