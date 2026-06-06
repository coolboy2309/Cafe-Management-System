@extends('admin.layout')
@section('title','Stock Movement')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div><strong>
                        @if(session()->has('message'))
                        {{session()->get('message')}}
                        @endif
                    </strong></div>
                <div class="table-responsive">
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

                                <td>@if($data->direction == 'IN')
                                    To -> {{ $data->location->name ?? 'N/A' }}</td>
                                    @else
                                    From -> {{ $data->location->name ?? 'N/A' }}
                                    @endif
                                <!-- Direction -->
                                <td>
                                    @if($data->direction == 'IN')
                                    <span style="color:green; font-weight:bold;">IN</span>
                                    @else
                                    <span style="color:red; font-weight:bold;">OUT</span>
                                    @endif
                                </td>

                                <!-- Quantity -->
                                <td>{{ $data->quantity }}</td>

                                <!-- Reason -->
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

                                <!-- Supplier -->
                                <td>{{ $data->supplier->name ?? '—' }}</td>

                                <!-- Date -->
                                <td>{{ $data->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection