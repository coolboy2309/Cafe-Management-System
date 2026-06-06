@extends('admin.layout')
@section('title','Kitchen Controller')
@section('content')

<style>
    .badge {
        font-size: 14px;
        padding: 6px 10px;
        border-radius: 6px;
    }
</style>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div><strong>
                    </strong></div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Total Items</th>
                                <th>Late Time</th>
                                <!-- <th>Late Items</th> -->
                                <th>Chef ID</th>
                                <th>Items</th>
                            </tr>
                        </thead>

                        @foreach($lateOrders as $order)
                        @php
                        $stat = $stats->where('order_number', $order->order_number)->first();
                        @endphp
                        <tbody>
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->items->count() }}</td>
                                <td>
                                    @php
                                    // Get KitchenStat for this order
                                    $stat = $stats->where('order_number', $order->order_number)->first();

                                    if($stat && $stat->finished_at) {
                                    $prepTime = $order->items->max('prep_time'); // longest prep item
                                    $delay = $stat->finished_at->diffInMinutes($order->created_at) - $prepTime;
                                    } else {
                                    $delay = null;
                                    }
                                    @endphp

                                    @if($delay !== null && $delay > 0)
                                    <span class="badge bg-danger">Late {{ $delay }} min</span>
                                    @else
                                    <span class="badge bg-success">On Time</span>
                                    @endif
                                </td>
                                <!-- <td>{{ $order->items->where('created_at', '<', now())->count() }}</td> -->

                                <td>{{$stat->chef_name ?? '-'}}</td>
                                <td>
                                    <ul>
                                        @foreach($order->items as $item)
                                        <li>{{ $item->name }} - : {{ $item->prep_time }} min</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>

                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection