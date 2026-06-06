@extends('admin.layout')
@section('title','Store')

@section('top')

<div class="block-body text-right">
    <button type="button" class="btn btn-success"><a href="{{route('admin.su')}}" style="color:white">Supplier</a> </button>
    <button type="button" class="btn btn-warning "><a href="{{route('admin.unit')}}" style="color:white">Unit</a> </button>
    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add Store</button>
    <!-- Modal-->
    <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Add To Store</strong>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                    <form action="{{url('storeAdd')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-control-label">Item Name</label>
                            <input type="text" name="storeName" placeholder="Item Name" class="form-control">
                        </div>
                        <label class="form-control-label">Unit</label>
                        <select name="storeUnit" class="form-control mb-8 mb-8" validate>
                            <option style="background-color: #2f2f2fff">Select Unit</option>

                            @foreach($unit as $unit)
                            <option style="background-color: #2f2f2fff" value="{{$unit->id}}">{{$unit->name}}</option>
                            @endforeach
                        </select>
                        <label class="form-control-label">Category</label>
                        <select name="storeCat" class="form-control mb-8 mb-8" validate>
                            <option style="background-color: #2f2f2fff">Select Category</option>

                            @foreach($cat as $cat)
                            <option style="background-color: #2f2f2fff" value="{{$cat->id}}">{{$cat->name}}</option>
                            @endforeach
                        </select>

                        <div class="form-group">
                            <label class="form-control-label">Cost</label>
                            <input type="number" name="cost" placeholder="Cost" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Price</label>
                            <input type="number" name="price" placeholder="Price" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Alert</label>
                            <input type="text" name="alert" placeholder="Alert Qty" class="form-control">
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                            <input type="submit" value="Add" class="btn btn-primary">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')

<style>
    /* quick CSS tweak for nice dropdown and modal look */
    #editItemModal .form-control {
        border-radius: 8px;
        padding: 8px;
    }

    #editItemModal .modal-header {
        background-color: #007bff;
        color: white;
        border-bottom: none;
    }

    #editItemModal .modal-content {
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:12px;">

            <form method="POST" action="{{ url('updateItem') }}">
                @csrf

                <input type="hidden" name="id" id="edit_id">

                <!-- HEADER -->
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"> Edit Item</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Item Name</label>
                            <input type="text" id="edit_name" name="name" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Category</label>
                            <select id="edit_category" name="category_id" class="form-control"></select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Unit</label>
                            <select id="edit_unit" name="unit_id" class="form-control"></select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Price</label>
                            <input type="number" id="edit_price" name="price" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Cost</label>
                            <input type="number" id="edit_cost" name="cost" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Min Stock</label>
                            <input type="number" id="edit_min_stock" name="min_stock" class="form-control">
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>

            </form>

        </div>
    </div>
</div>

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
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Cost</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Alert Stock</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        @foreach($item as $i)

                        <tbody>
                            <tr @if($i->qty < $i->min_stock) style="background-color: #ffa8af;color:black" @endif >
                                    <th>{{$i->name}}</th>
                                    <th>{{$i->category->name ?? 'No Category'}}</th>
                                    <th>{{$i->price}}</th>
                                    <th>{{$i->cost}}</th>
                                    <th>
                                        {{$i->qty}}
                                    </th>
                                    <th>{{$i->unit->symbol ?? 'No Unit'}}</th>
                                    <th>{{$i->min_stock}}</th>
                                    <th>
                                        @if($i->is_active == 1)
                                        <span style="color:green">Enable</span>
                                        @endif
                                        @if($i->is_active == 0)
                                        <span style="color:red">Disable</span>
                                        @endif
                                    </th>
                                    <th>{{ $i->created_at->format('M d, Y') }}</th>

                                    <td><button data-toggle="dropdown" type="button" class="btn btn-outline-secondary dropdown-toggle"><span class="caret"></span>:</button>
                                        <div class="dropdown-menu">
                                            <a href="#"
                                                class="dropdown-item editBtn"
                                                data-id="{{ $i->id }}"
                                                data-toggle="modal"
                                                data-target="#editItemModal">
                                                Edit
                                            </a>
                                            <a href="{{url('deleitem',$i->id)}}" class="dropdown-item">Delete</a>
                                            @if($i->is_active == 1)
                                            <a href="{{url('deactive_i',$i->id)}}" class="dropdown-item">Disable</a>
                                            @endif
                                            @if($i->is_active == 0)
                                            <a href="{{url('active_i',$i->id)}}" class="dropdown-item">Enable</a>
                                            @endif
                                            <a href="#" class="dropdown-item transferBtn" data-toggle="modal" data-target="#transferModal" data-item="{{ $i->id }}" data-item-name="{{ $i->name }}"> Transfer</a>
                                            <a href="#" class="dropdown-item addQtyBtn" data-toggle="modal" data-target="#addQtyModal" data-item="{{ $i->id }}" data-item-name="{{ $i->name }}"> Add Qty</a>
                                        </div>
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

<!-- Transfer Modal -->
<div id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <strong id="transferModalLabel" class="modal-title">Transfer Stock</strong>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('transferStock') }}" method="POST">
                    @csrf

                    <!-- Item -->
                    <div class="form-group">
                        <label>Select Item</label>

                        <select id="transferItem" name="item_id" class="form-control" required>
                            <option value="">-- Select Item --</option>
                            @foreach($item as $i)
                            <option value="{{ $i->id }}">{{ $i->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- To Location -->
                    <div class="form-group">
                        <label>To Location</label>
                        <select name="to_location_id" class="form-control" required>
                            <option value="">-- Select Destination --</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                            <option value="waste">Waste / Damaged</option>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" step="0.01" name="quantity" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                        <input type="submit" value="Transfer" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Quantity Modal -->
<div id="addQtyModal" tabindex="-1" role="dialog" class="modal fade text-left" onsubmit="disableBtn(this)">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title">Add Quantity</strong>
                <button type="button" data-dismiss="modal" class="close">×</button>
            </div>

            <div class="modal-body">
                <form action="{{ url('addQty') }}" method="POST">
                    @csrf

                    <!-- Hidden Item ID -->
                    <input type="hidden" name="item_id" id="addQtyItemId">

                    <!-- Item Name (readonly) -->
                    <div class="form-group">
                        <label>Item</label>
                        <input type="text" id="addQtyItemName" class="form-control" readonly>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" step="0.01" name="quantity" class="form-control" required>
                    </div>

                    <!-- Supplier (optional for now) -->
                    <div class="form-group">
                        <label>Supplier (optional)</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- Select Supplier --</option>
                            @foreach($sup as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                        <input type="submit" value="Add Stock" id="submitBtn" class="btn btn-primary">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const transferButtons = document.querySelectorAll('.transferBtn');
        const itemSelect = document.getElementById('transferItem');

        transferButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.dataset.item;
                if (itemSelect) {
                    itemSelect.value = itemId; // set selected item
                }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.addQtyBtn');

        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('addQtyItemId').value = this.dataset.item;
                document.getElementById('addQtyItemName').value = this.dataset.itemName;
            });
        });
    });

    function disableBtn(form) {
        let btn = document.getElementById('submitBtn');

        btn.disabled = true;
        btn.value = "Processing...";
    }

    document.addEventListener('DOMContentLoaded', function() {

        // Attach click event to all edit buttons
        const editButtons = document.querySelectorAll('.editBtn');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                fetch(`/get-item/${id}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.json();
                    })
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        const item = data.item;
                        const cats = data.categories;
                        const units = data.units;

                        // Fill input fields
                        document.getElementById('edit_id').value = item.id;
                        document.getElementById('edit_name').value = item.name;
                        document.getElementById('edit_price').value = item.price;
                        document.getElementById('edit_cost').value = item.cost;
                        document.getElementById('edit_min_stock').value = item.min_stock;

                        // Populate Category select
                        const catSelect = document.getElementById('edit_category');
                        catSelect.innerHTML = '';
                        cats.forEach(c => {
                            const option = document.createElement('option');
                            option.value = c.id;
                            option.textContent = c.name;
                            if (c.id === item.category_id) option.selected = true;
                            catSelect.appendChild(option);
                        });

                        // Populate Unit select
                        const unitSelect = document.getElementById('edit_unit');
                        unitSelect.innerHTML = '';
                        units.forEach(u => {
                            const option = document.createElement('option');
                            option.value = u.id;
                            option.textContent = u.symbol;
                            if (u.id === item.unit_id) option.selected = true;
                            unitSelect.appendChild(option);
                        });

                        // Show modal
                        $('#editItemModal').modal('show'); // still needs Bootstrap JS
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        alert("Failed to fetch item data");
                    });
            });
        });

    });

    function updateItemAjax() {
        const form = document.querySelector('#editItemModal form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const data = {
                id: document.getElementById('edit_id').value,
                name: document.getElementById('edit_name').value,
                category_id: document.getElementById('edit_category').value,
                unit_id: document.getElementById('edit_unit').value,
                price: document.getElementById('edit_price').value,
                cost: document.getElementById('edit_cost').value,
                min_stock: document.getElementById('edit_min_stock').value
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/updateItem', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        alert('Item updated ');
                        location.reload(); // or re-fetch table dynamically
                    } else {
                        alert('Update failed ');
                    }
                })
                .catch(err => console.error(err));
        });
    }
</script>


@endsection