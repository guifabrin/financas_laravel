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
  <div class="container-fluid">
    <div class="row">
        <div id="chartTransactions" class="col-md-6" style="height: 300px;"></div>
        <div id="chartTransactionsByCategory" class="col-md-6" style="height: 300px;"></div>
    </div>
  </div>
@endsection

@section('script')
<script>
  $('input[type=text][name=description]').bind("keyup", function(){
    $('input[type=hidden][name=description]').val(this.value);
  });
</script>

<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

<script>
  var transactions = {!! $transactions !!};
  var categories = {!! $categories !!};
  var category_transactions = {!! json_encode($category_transactions) !!};
  $(function(){
    $(transactions).each(function(indice, item){
      item.value = item.value*1;
      item.date = new Date(Date.parse(item.date));
      item.date.setHours(0, 0, 0, 0);
    }) 
    //chartTransactions();
    chartTransactionsByCategory();
  });

  function chartTransactions(){
    var data = [];

    var dataSeriesUp = { type: "area" };
    var dataSeriesDown = { type: "area" };
    var dataSeriesAvg = { type: "area" };

    var dataDatePointsUp = [];
    var dataDatePointsDown = [];
    var dataDatePointsAvg = [];

    $(transactions).each(function(indice, item){
      if (dataDatePointsUp[item.date] == undefined){
        dataDatePointsUp[item.date] = 0;
      }      
      if (dataDatePointsDown[item.date] == undefined){
        dataDatePointsDown[item.date] = 0;
      }    
      if (dataDatePointsAvg[item.date] == undefined){
        dataDatePointsAvg[item.date] = 0;
      }
      if (item.value > 0){
        dataDatePointsUp[item.date] += item.value
      } else {
        dataDatePointsDown[item.date] += item.value
      }
      dataDatePointsAvg[item.date] += item.value
    });

    var dataPointsUp = [];
    for (var upKey in dataDatePointsUp){
      dataPointsUp.push({x: new Date(Date.parse(upKey)), y: dataDatePointsUp[upKey]});
    }
    var dataPointsDown = [];
    for (var downKey in dataDatePointsDown){
      dataPointsDown.push({x: new Date(Date.parse(downKey)), y: dataDatePointsDown[downKey]});
    }
    var dataPointsAvg = [];
    for (var avgKey in dataDatePointsAvg){
      dataPointsAvg.push({x: new Date(Date.parse(avgKey)), y: dataDatePointsAvg[avgKey]});
    }
    dataSeriesUp.dataPoints = dataPointsUp;
    dataSeriesDown.dataPoints = dataPointsDown;
    dataSeriesAvg.dataPoints = dataPointsAvg;
    data.push(dataSeriesUp);
    data.push(dataSeriesDown);
    data.push(dataSeriesAvg);
    var options = {
      zoomEnabled: true,
      animationEnabled: true,
      title: {
        text: "{{__('transactions.title')}}"
      },
      axisX: {
        labelAngle: -20
      },
      axisY: {
        includeZero: true
      },
      data: data
    };
    $("#chartTransactions").CanvasJSChart(options);
  }

  function chartTransactionsByCategory(){
    var data = [];

    $(category_transactions).each(function(indice, item){
      if (data[item.category_id] == undefined){
        data[item.category_id] = 0;
      }
      data[item.category_id] += item.value;
    });
    console.log(data);
    var options = {
      title:{
        text: "Gaming Consoles Sold in 2012"
      },
      legend: {
        maxWidth: 350,
        itemWidth: 120
      },
      data: [
      {
        type: "pie",
        showInLegend: true,
        legendText: "{indexLabel}",
        dataPoints: [
          { y: 4181563, indexLabel: "PlayStation 3" },
          { y: 2175498, indexLabel: "Wii" },
          { y: 3125844, indexLabel: "Xbox 360" },
          { y: 1176121, indexLabel: "Nintendo DS"},
          { y: 1727161, indexLabel: "PSP" },
          { y: 4303364, indexLabel: "Nintendo 3DS"},
          { y: 1717786, indexLabel: "PS Vita"}
        ]
      }
      ]
    }
  }
</script>
@endsection