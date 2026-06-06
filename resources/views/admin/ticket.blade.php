<div class="ticket">
    <h4>
        @if($department == 1)
            KITCHEN
        @elseif($department == 2)
            BAR / STORE
        @else
            DEPARTMENT {{ $department }}
        @endif
    </h4>

    <p>Order #{{ $order->id }}</p>
    <hr>

    @foreach($items as $item)
        <p>{{ $item->quantity }} x {{ $item->name }}</p>
    @endforeach

    <hr>
</div>