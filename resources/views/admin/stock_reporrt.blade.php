@extends('admin.layout')
@section('title','Stock Management')

@section('top')
<div class="row mb-3" style="margin-top: 10px">
    <div class="col-lg-3">
        <select id="locationSelect" class="form-control">
            <option value="Kitchen" {{ $locationName === 'Kitchen' ? 'selected' : '' }}>Kitchen</option>
            <option value="Bar" {{ $locationName === 'Bar' ? 'selected' : '' }}>Bar</option>
        </select>
    </div>
</div>

<script>
    document.getElementById('locationSelect').addEventListener('change', function() {
        let location = this.value;
        window.location.href = "{{ url('stock_food') }}?location=" + location;
    });
</script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="block">
        <div class="title"><strong>Stock List ({{ $locationName }})</strong></div>
        <div class="block-body table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['qty'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection