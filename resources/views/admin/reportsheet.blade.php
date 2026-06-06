@extends('admin.layout')
@section('title','Report')
<style>
    #report-content {
        min-height: 300px;
        background-color: #ffffff;
        border-radius: 0.375rem;
        border: solid #fff
    }

    .att {
        margin-right: 10px;
        border-radius:15px;
    }

    .niggg {
        border: 1px solid #3893f4;
    }

    #barChartCustom3 {
        max-height: 600px;
        
    }

</style>

@section('content')
<div class="container-fluid">
    <div class="mb-4 d-flex flex-wrap gap-2">
        <button class="btn btn-secondary att report-btn active" data-report="month">Monthly Report</button>
        <button class="btn btn-secondary att report-btn" data-report="stock">Stocksss Report</button>
        <button class="btn btn-secondary att report-btn" data-report="category">Category Report</button>
        <button class="btn btn-secondary att report-btn" data-report="profit">Profit Report</button>
    </div>
    <div id="report-content" class="card shadow-sm p-3">

        <div id="monthly-report">
            @if($totalOrders == 0)

            <div class="alert alert-warning">
                No report data available for this month.
            </div>

            @endif
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h3 class="mb-1 text-dark">
                        Monthly Business Report
                    </h3>
                    <p class="text-muted mb-0">
                        {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}
                    </p>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-success">
                        Export Excel
                    </button>
                    <button class="btn btn-danger">
                        Export PDF
                    </button>
                </div>
            </div>
            <div class="d-flex gap-4 align-items-center flex-wrap mb-4" style="color:black">
                <label></label>
                <select id="monthFilter" class="form-select">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                        @endfor
                </select>
                <label></label>
                <select id="yearFilter" class="form-select">
                    @for ($y = now()->year-5; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                </select>

                <button id="filterBtn" style="margin-left:10px" class="btn btn-primary">Filter</button>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block">
                        <div class="progress-details  align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Total Sale`s</strong>
                            </div>
                            <div class="number {{ $profitMargin < 0 ? 'text-danger' : 'text-success' }}"> {{ number_format($totalSales, 2) }} Br</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details  align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Gross Profit</strong>
                            </div>
                            <div class="number {{ $netProfit < 0 ? 'text-danger' : 'text-success' }}"> {{ number_format($grossProfit, 2) }} Br</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details  align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Net Profit</strong>
                            </div>
                            <div class="number {{ $netProfit < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($netProfit, 2) }} Br</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Avg Order Value</strong>
                            </div>
                            <div class="number {{ $avgOrderValue < 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($avgOrderValue, 2) }} Br
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details  align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Expense</strong>
                            </div>
                            <div class="number {{ $expense < 0 ? 'text-success' : 'text-danger' }}">{{ number_format($expense, 2)}} Br</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Profit Margin</strong>
                            </div>
                            <div class="number {{ $profitMargin < 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($profitMargin, 2) }} %
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Sales Growth</strong>
                            </div>
                            <div class="number number {{ $salesGrowth < 0 ? 'text-danger' : 'text-success' }}">
                                {{ $salesGrowth !== null ? number_format($salesGrowth, 2).' %' : 'NEW' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Profit Growth</strong>
                            </div>
                            <div class="number number {{ $profitGrowth < 0 ? 'text-danger' : 'text-success' }}">
                                {{ $profitGrowth !== null ? number_format($profitGrowth, 2).' %' : 'NEW' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Cash</strong>
                            </div>
                            <div class="number dashtext-4">
                                {{ number_format($cashPercent,1) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Bank</strong>
                            </div>
                            <div class="number dashtext-4">
                                {{ number_format($bankPercent,1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <section class="no-padding-bottom">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card mb-1 p-3" style="background-color: #3893f4; color: #fff; border: 1px solid #fff; border-radius:12px;">
                            <h5>Insights With Previous Month</h5>
                            <ul>
                                @forelse($insights as $insight)
                                <li>{{ $insight }}</li>
                                @empty
                                <li>No insights available yet</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-1 p-3" style="background-color: #3893f4; color: #fff; border: 1px solid #fff; border-radius:12px;">
                            <h5>Financial Health</h5>
                            <ul>
                                <li>Best Sales Day : {{ $bestSalesDay }}</li>
                                <li>Highest sales reached : {{ number_format($bestSalesValue, 2) }} Br</li>
                                <li>Expense Ratio : {{ number_format($expenseRatio, 1) }}%, Percentage of revenue consumed by expenses</li>
                            </ul>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card p-3" style="background-color:white;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5><strong>Sales & Profit Trend</strong></h5>

                                        <!-- Toggle Buttons -->
                                        <div class="btn-group" role="group">
                                            <button style="color: #3893f4" class="btn btn-sm btn-outline-light toggle-btn active" data-type="all">All</button>
                                            <button style="color: #3893f4" class="btn btn-sm btn-outline-light toggle-btn" data-type="sales">Sales</button>
                                            <button style="color: #3893f4" class="btn btn-sm btn-outline-light toggle-btn" data-type="profit">Profit</button>
                                        </div>
                                    </div>
                                    <canvas id="barChartCustom3"></canvas>
                                </div>
                            </div>

                        </div>
                    </div>
            </section>
        </div>
        <!-- Monthly report finish -->

        <div id="stock-report" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h3 class="mb-1 text-dark">
                        Stock Report
                    </h3>
                    <p class="text-muted mb-0">
                        {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}
                    </p>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-success">
                        Export Excel
                    </button>
                    <button class="btn btn-danger">
                        Export PDF
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details  align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Inventory Value</strong>
                            </div>
                            <div class="number {{ $inventoryValue > 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($inventoryValue, 2) }} Br
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block">
                        <div class="progress-details  align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Total Items`s</strong>
                            </div>
                            <div class="number {{ $totalItem > 0 ? 'text-success' : 'text-danger' }}"> {{ $totalItem }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details  align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Total Stock Qty</strong>
                            </div>
                            <div class="number {{ $outStock > 0 ? 'text-success' : 'text-success' }}"> {{ number_format($itemStock) }} Qty</div>
                        </div>
                    </div>
                </div>
                <!-- Stock Health -->
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Stock Health</strong>
                            </div>
                            <div class="number {{ $stockHealth < 60 ? 'text-danger' : ($stockHealth < 80 ? 'text-warning' : 'text-success') }}">
                                {{ number_format($stockHealth, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details  align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Low stock</strong>
                            </div>
                            <div class="number {{ $lowstock > 5 ? 'text-warning' : 'text-success' }}">{{ number_format($lowstock) }} Item</div>
                        </div>
                    </div>
                </div>
                <!-- Low Stock Percentage -->
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div> <strong>Low Stock %</strong>
                            </div>
                            <div class="number {{ $lowStockPercent > 30 ? 'text-danger' : ($lowStockPercent > 15 ? 'text-warning' : 'text-success') }}">
                                {{ number_format($lowStockPercent, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Out of Stock</strong>
                            </div>
                            <div class="number {{ $outStock > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($outStock) }} Item</div>
                        </div>
                    </div>
                </div>
                <!-- Restock Required -->
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block border-start border-4">
                        <div class="progress-details align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"></div><strong>Restock Required</strong>
                            </div>
                            <div class="number {{ $restockRequired > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $restockRequired }} Items
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card p-3"
                        style="background-color: #3893f4; color: #fff; border: 1px solid #fff; border-radius:9px;">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                Inventory Insights
                            </h5>
                            <small>
                                Live Inventory Analysis
                            </small>
                        </div>
                        <ul class="mb-0">
                            @foreach($stockInsights as $insight)
                            <li class="mb-2">
                                {{ $insight }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-6">
                    <div class="block margin-bottom-sm">
                        <div class="title"><strong>Stock Alert</strong></div>
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Current Qty</th>
                                        <th>Min Stock</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stockAlerts as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->min_stock }}</td>
                                        <td><span class="badge bg-{{ $item->status_color }}" style="color:white">{{ $item->status }}</span></td>
                                        <td>{{ $item->action }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No stock alerts detected
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Location</th>
                                    <th>Direction</th>
                                    <th>Qty</th>
                                    <th>Reason</th>
                                    <th>Supplier</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($stock as $data)
                                <tr>

                                    <td>{{ $data->item->name ?? 'N/A' }}</td>

                                    <td>
                                        @if($data->direction == 'IN')
                                        To -> {{ $data->location->name ?? 'N/A' }}
                                        @else
                                        From -> {{ $data->location->name ?? 'N/A' }}
                                        @endif
                                    </td>

                                    <td>
                                        @if($data->direction == 'IN')
                                        <span style="color:green; font-weight:bold;">IN</span>
                                        @else
                                        <span style="color:red; font-weight:bold;">OUT</span>
                                        @endif
                                    </td>

                                    <td>{{ $data->quantity }}</td>

                                    <td>
                                        @if($data->reason == 'transfer')
                                        Transfer
                                        @elseif($data->reason == 'waste')
                                        Waste / Damaged
                                        @elseif($data->reason == 'purchase')
                                        Purchase
                                        @else
                                        {{ $data->reason }}
                                        @endif
                                    </td>

                                    <td>{{ $data->supplier->name ?? '—' }}</td>

                                    <td>{{ $data->created_at->format('M d, Y H:i') }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock report finish -->
        <div id="category-report" style="display:none;">
            <h3>Category Report</h3>
        </div>
        <!-- Category report finish -->
        <div id="profit-report" style="display:none;">
            <h3>Profit Report</h3>
        </div>
        <!-- Profit report finish -->
    </div>

</div>
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script>
    const buttons = document.querySelectorAll('.report-btn');

    const reports = {
        month: document.getElementById('monthly-report'),
        stock: document.getElementById('stock-report'),
        category: document.getElementById('category-report'),
        profit: document.getElementById('profit-report')
    };

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {

            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            Object.values(reports).forEach(report => {
                report.style.display = 'none';
            });

            const reportType = this.dataset.report;

            reports[reportType].style.display = 'block';
        });
    });
    document.getElementById('filterBtn').addEventListener('click', function() {

        this.innerHTML = 'Loading...';
        this.disabled = true;

        const month = document.getElementById('monthFilter').value;
        const year = document.getElementById('yearFilter').value;

        const url = new URL(window.location.href);

        url.searchParams.set('month', month);
        url.searchParams.set('year', year);

        window.location.href = url;
    });
</script>
<script>
    // Bar Chart with toggle
    let barChart;
    const ctxBar = document.getElementById("barChartCustom3").getContext("2d");

    const chartData = {
        labels: @json($barLabels),
        datasets: [{
                label: "Total Sale (Br)",
                data: @json($totalSaleData),
                backgroundColor: '#1A56DB',
                hidden: false
            },
            {
                label: "Gross Profit (Br)",
                data: @json($grossData),
                backgroundColor: '#00C2FF',
                hidden: false
            },
            {
                label: "Net Profit (Br)",
                data: @json($netData),
                backgroundColor: '#38BDF8',
                hidden: false
            },
            {
                label: "Expense (Br)",
                data: @json($expenseData),
                backgroundColor: '#ffd500',
                hidden: false
            }
        ]
    };

    barChart = new Chart(ctxBar, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw.toLocaleString() + ' Br';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Toggle Functionality
    document.querySelectorAll('.toggle-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active from all buttons
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const type = this.dataset.type;

            barChart.data.datasets.forEach(dataset => {
                if (type === 'all') {
                    dataset.hidden = false;
                } else if (type === 'sales' && dataset.label.includes('Sale')) {
                    dataset.hidden = false;
                } else if (type === 'profit' && (dataset.label.includes('Profit') || dataset.label.includes('Expense'))) {
                    dataset.hidden = false;
                } else {
                    dataset.hidden = true;
                }
            });

            barChart.update();
        });
    });
</script>
@endsection