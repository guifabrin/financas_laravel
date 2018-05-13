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
          <ul class="nav nav-tabs">
            @foreach ($years as $year)
              <li {!!$actualYear==$year?'class="active"':''!!}>
                <a href="/accounts?year={{$year}}">{{$year}}</a>
              </li>
            @endforeach
          </ul>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr class="active">
                  <th colspan="3">{{__('common.description')}}</th>
                  @for ($i=0; $i<12; $i++)
                    <th colspan="2">
                      {{__('common.months.'.$i)}}   
                    </th>
                  @endfor
                </tr>
              </thead>
              <tbody>
                @foreach($accounts as $account)
                  <tr>
                    <th class="active" rowspan="2">
                      {{$account->description}}
                    </th>
                    <th class="active text-right" rowspan="2" style="vertical-align: middle;">
                      <a title="{{__('common.edit')}} {{__('accounts.account')}}" href="/accounts/{{$account->id}}/edit"><i class="fa fa-pencil"/></i></a>
                    </th>
                    <th class="active text-right" rowspan="2" style="vertical-align: middle;">
                      <a title="{{__('common.remove')}} {{__('accounts.account')}}" href="/accounts/{{$account->id}}/confirm"><i class="fa fa-trash"/></i></a>
                    </th>
                    @for($i=0; $i<12; $i++) 
                      <td class="text-right" rowspan="2" style="vertical-align: middle;">
                        <a style="margin-right: 5px;" title="{{__('transactions.title')}}" href="/account/{{$account->id}}/transactions?date_init={{$dateInit[$i]}}&date_end={{$dateEnd[$i]}}">
                          <i class="fa fa-list"></i>
                        </a>
                      </td>
                      <td class="text-right">
                        {!!format_money($monthValueAccount[$account->id][$i])!!}
                      </td>
                    @endfor
                  </tr>
                  <tr>
                    @for($i=0; $i<12; $i++)
                      <td class="text-right">
                        @if ($monthValueAccountNotPaid[$account->id][$i]>0)
                          {!!format_money($monthValueAccountNotPaid[$account->id][$i])!!}
                        @else
                          {!!format_money(0)!!}
                        @endif
                      </td>
                    @endfor
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr class="active">
                  <th class="text-right" colspan="3">
                    {{__('accounts.totals_paid')}}
                  </th>
                  @foreach($sumPaid as $sum)
                    <th class="text-right" colspan="2">
                      {!!format_money($sum)!!}
                    </th>
                  @endforeach
                </tr>
                <tr class="active">
                  <th class="text-right" colspan="3">
                    {{__('accounts.totals_not_paid')}}
                  </th>
                  @foreach($sumNotPaid as $sum)
                    <th class="text-right" colspan="2">
                      {!!format_money($sum)!!}
                    </th>
                  @endforeach
                </tr>
                <tr class="active">
                  <th class="text-right" colspan="3">
                    {{__('accounts.totals')}}
                  </th>
                  @for($i=0; $i<12; $i++)
                    <th class="text-right" colspan="2">
                      {!!format_money($sumNotPaid[$i]+$sumPaid[$i])!!}
                    </th>
                  @endfor
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script src="{{ asset('js/accounts/index.js') }}"></script>
@endsection