@extends('layouts.app')

@section('content')
<div class="container-fluid invoices-container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          {{__('accounts.title')}}
          <a href="/accounts/create">{{__('common.add')}}</a>
        </div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif

          <div class='invoice-header'>
          </div>
          <ul class="nav nav-tabs">
            @foreach ($account->invoices()->get() as $i)
              <?php $valuep = 0 ?>
              <?php $valuen = 0 ?>
              @foreach ($i->transactions()->orderBy('date')->get() as $t)
                  <?php $valuep += ($t->value>0) ? $t->value : 0?>
                  <?php $valuen += ($t->value<0) ? $t->value : 0 ?>
              @endforeach
              <li>
                <a data-toggle="tab" class="dropdown-toggle" href="#invoice_{{$i->id}}">
                  <span class='invoice-title'>{{$i->description}}</span>
                  <span class='invoice-value'>{!!format_money($valuep)!!}</span>
                  <span class='invoice-value'>{!!format_money($valuen)!!}</span>
                </a>
              </li>
            @endforeach
          </ul>
          <div class="tab-content">

            @foreach ($account->invoices()->get() as $i)
            <div id="invoice_{{$i->id}}" class="tab-pane fade in">
              <table class='table'>
                <thead>
                  <tr>
                    <th>{{__('common.date')}}</th>
                    <th>{{__('common.description')}}</th>
                    <th>{{__('transactions.value')}}</th>
                    <th>{{__('transactions.paid')}}</th>
                    <th>{{__('common.actions')}}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($i->transactions()->orderBy('date')->get() as $transaction)
                    <tr>
                      <td>{!!format_date($transaction->date)!!}</td>
                      <td>{{$transaction->description}}</td>
                      <td>{!!format_money($transaction->value)!!}</td>
                      <td>
                        <div class="checkbox">
                          <label>
                            <input disabled="true" type="checkbox" {{$transaction->paid?"checked='true'":""}}/>
                          </label>
                        </div>
                      </td>
                      <td>
                        <a href="/account/{{$account->id}}/transaction/{{$transaction->id}}/edit{{ (isset($_GET['date_init']) && isset($_GET['date_end'])) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '' }}">{{__('common.edit')}}</a>
                        <a href="/account/{{$account->id}}/transaction/{{$transaction->id}}/confirm{{isset($_GET['date_init']) && isset($_GET['date_end']) ?'?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '' }}">{{__('common.remove')}}</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endforeach

          </div>

          <div class='invoice-body'>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection