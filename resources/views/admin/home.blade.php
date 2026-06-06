@extends('admin.layout')
@section('title','Admin Dashboard')

@section('content')

<div class="container-fluid">
  <div class="row">

    <div class="col-md-3 col-sm-6">
      <div class="statistic-block block">
        <div class="progress-details  align-items-end justify-content-between">
          <div class="title">
            <div class="icon"></div><strong>Today’s Sales</strong>
          </div>
          <div class="number dashtext-1">{{ number_format($totalSales, 2) }} Br</div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="statistic-block block">
        <div class="progress-details  align-items-end justify-content-between">
          <div class="title">
            <div class="icon"></div><strong>Cash</strong>
          </div>
          <div class="number dashtext-1">{{ number_format($cash, 2) }} Br</div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="statistic-block block">
        <div class="progress-details  align-items-end justify-content-between">
          <div class="title">
            <div class="icon"></div><strong>Bank</strong>
          </div>
          <div class="number dashtext-1">{{ number_format($bank, 2) }} Br</div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="statistic-block block">
        <div class="progress-details  align-items-end justify-content-between">
          <div class="title">
            <div class="icon"></div><strong>Tip // return </strong>
          </div>
          <div class="number dashtext-1">{{ number_format($cashOut, 2) }} Br</div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="statistic-block block">
        <div class="progress-details  align-items-end justify-content-between">
          <div class="title">
            <div class="icon"></div><strong>Expense`s</strong>
          </div>
          <div class="number dashtext-1">{{ number_format($expense, 2) }} Br</div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="statistic-block block">
        <div class="progress-details  align-items-end justify-content-between">
          <div class="title">
            <div class="icon"></div><strong>Gross Profit</strong>
          </div>
          <div class="number dashtext-1">{{ number_format($gross_pro, 2) }} Br</div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="statistic-block block">
        <div class="progress-details  align-items-end justify-content-between">
          <div class="title">
            <div class="icon"></div><strong>Net Profit</strong>
          </div>
          <div class="number dashtext-1">{{ number_format($profit, 2) }} Br</div>
        </div>
      </div>
    </div>


    <div class="col-md-1 col-sm-6">
      <div class="statistic-block block">
        <div class="progress-details  align-items-end justify-content-between">
          <div class="title">
            <div class="icon"></div><strong>Orders</strong>
          </div>
          <div class="number dashtext-1">{{ $totalOrders }}</div>
        </div>
      </div>
    </div>

    <div class="col-md-2 col-sm-6">
      <div class="statistic-block block">
        <div class="progress-details  align-items-end justify-content-between">
          <div class="title">
            <div class="icon"></div><strong>Low Stock</strong>
          </div>
          <div class="number dashtext-1">{{ $lowStock }}</div>
        </div>
      </div>
    </div>

  </div>
</div>

<section class="no-padding-bottom">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-7">
        <div class="line-cahrt block">
          <div class="title"><strong>Today Sale`s</strong></div>
          <canvas id="lineChart"></canvas>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="pie-chart chart block">
          <div class="title"><strong>Cash Vs Bank</strong></div>
          <br><br>
          <div class="pie-chart chart margin-bottom-sm">
            <canvas id="pieChartCustom1"></canvas>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="no-padding-top">
  <div class="container-fluid">
    <div class="row">

      <div class="col-lg-6">
        <div class="block margin-bottom-sm">
          <div class="title"><strong>Stock Alert</strong></div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Item Name</th>
                  <th>Qty</th>
                  <th>Alert Stock</th>
                </tr>
              </thead>
              @foreach($item as $i)
              <tbody>
                <tr>
                  <td>{{$i->name}}</td>
                  <td>{{$i->qty}}</td>
                  <td>{{$i->min_stock}}</td>
                </tr>
              </tbody>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script>
  let hourlyLabels = @json($hourlyLabels);
  let hourlyTotalSaleData = @json($hourlyTotalSaleData);
  let hourlyGrossData = @json($hourlyGrossData);
  let hourlyNetData = @json($hourlyNetData);
  let hourlyExpenseData = @json($hourlyExpenseData);

  var ctx = document.getElementById("lineChart").getContext("2d");

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: @json($hourlyLabels),
      datasets: [{
          label: "Total Sale (Br)",
          data: hourlyTotalSaleData,
          borderColor: '#1A56DB',
          backgroundColor: 'transparent'
        },
        {
          label: "Gross Profit (Br)",
          data: hourlyGrossData,
          borderColor: '#00C2FF',
          backgroundColor: 'transparent'
        },
        {
          label: "Net Profit (Br)",
          data: hourlyNetData,
          borderColor: '#38BDF8',
          backgroundColor: 'transparent'
        },
        {
          label: "Expense (Br)",
          data: hourlyExpenseData,
          borderColor: '#ffd500',
          backgroundColor: 'transparent'
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.dataset.label + ": Br " + context.raw.toLocaleString();
            }
          }
        }
      }
    }
  });
</script>

<script>
  // Pie chart data from backend
  let pieLabels = @json($pieData['labels']);
  let pieValues = @json($pieData['values']);

  var ctxPie = document.getElementById("pieChartCustom1").getContext("2d");

  new Chart(ctxPie, {
    type: 'pie',
    data: {
      labels: pieLabels,
      datasets: [{
        data: pieValues,
        backgroundColor: ['#1A56DB', '#00C2FF'], // purple for cash, red for bank (you can change)
        borderColor: ['#fff', '#fff'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            font: {
              size: 14
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.label + ': Br ' + context.raw.toLocaleString();
            }
          }
        }
      }
    }
  });
</script>

@endsection