@extends('admin.layout')
@section('title','Menu Ingredients')

@section('content')
<div class="container-fluid">
    <div class="row">

        <!-- LEFT SIDE: Add ingredient to menu -->
        <div class="col-lg-5">
            <div class="block">
                <div class="title"><strong>Add Ingredient to Menu</strong></div>
                <div class="block-body">
                    <!-- Form now only handles Save All -->
                    <form id="menuIngredientForm" action="{{ url('menu_ingredient_store_all') }}" method="POST">
                        @csrf

                        <!-- Select Menu -->
                        <div class="form-group">
                            <label>Menu</label>
                            <select id="menuSelect" name="menu_id" class="form-control" required>
                                <option value="">Select Menu</option>
                                @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Ingredient -->
                        <div class="form-group">
                            <label>Ingredient</label>
                            <select id="ingredientSelect" class="form-control" required>
                                <option value="">Select Ingredient</option>
                                @foreach($ingredients as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" step="0.01" id="quantityInput" class="form-control" placeholder="Enter quantity" required>
                        </div>

                        <div class="form-group">
                            <button type="button" id="addToList" class="btn btn-primary ">Add to List</button>
                        </div>

                        <!-- Cached list -->
                        <div class="form-group mt-3">
                            <h5>Pending Ingredients</h5>
                            <table class="table table-bordered" id="cachedTable">
                                <thead>
                                    <tr>
                                        <th>Ingredient</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- JS will fill this -->
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save All</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: List of ingredients for menus -->
        <div class="col-lg-7">
            <div class="block">
                <div class="title"><strong>Menu Ingredients List</strong></div>
                <div class="block-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Ingredients</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menus->sortBy('name') as $menu)
                                @php
                                // Get all ingredients for this menu
                                $menuItems = $menu_ingredients->where('menu_id', $menu->id);
                                @endphp
                                @if($menuItems->count() > 0)
                                <tr>
                                    <td>{{ $menu->name }}</td>
                                    <td>
                                        <ul style="padding-left: 15px; margin-bottom: 0;">
                                            @foreach($menuItems as $mi)
                                            <li style="margin-bottom:4px;">
                                                {{ $mi->item->name }} : {{ $mi->quantity }}
                                                <a href="{{ url('menu_ingredient_edit/'.$mi->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="{{ url('menu_ingredient_delete/'.$mi->id) }}" class="btn btn-sm btn-danger">Delete</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<!-- JS for caching -->
<script>
    let cachedIngredients = [];

    document.getElementById('addToList').addEventListener('click', function() {
        const menuSelect = document.getElementById('menuSelect');
        const ingredientSelect = document.getElementById('ingredientSelect');
        const quantityInput = document.getElementById('quantityInput');

        const menuId = menuSelect.value;
        const ingredientId = ingredientSelect.value;
        const ingredientText = ingredientSelect.options[ingredientSelect.selectedIndex].text;
        const quantity = parseFloat(quantityInput.value);

        if (!menuId || !ingredientId || !quantity) {
            alert('Please select menu, ingredient and quantity.');
            return;
        }

        // Add to cache
        cachedIngredients.push({
            ingredient_id: ingredientId,
            ingredient_name: ingredientText,
            quantity: quantity
        });

        renderCache();
        quantityInput.value = ''; // reset qty
    });

    function renderCache() {
        const tbody = document.querySelector('#cachedTable tbody');
        tbody.innerHTML = '';

        cachedIngredients.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td>${item.ingredient_name}</td>
            <td>${item.quantity}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeFromCache(${index})">Remove</button>
            </td>
        `;
            tbody.appendChild(tr);
        });
    }

    function removeFromCache(index) {
        cachedIngredients.splice(index, 1);
        renderCache();
    }

    // On form submit, append hidden inputs for all cached items
    document.getElementById('menuIngredientForm').addEventListener('submit', function(e) {
        const form = e.target;

        cachedIngredients.forEach((item, idx) => {
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `ingredients[${idx}][item_id]`;
            inputId.value = item.ingredient_id;
            form.appendChild(inputId);

            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = `ingredients[${idx}][quantity]`;
            inputQty.value = item.quantity;
            form.appendChild(inputQty);
        });

        if (cachedIngredients.length === 0) {
            e.preventDefault();
            alert('No ingredients to save!');
        }
    });
</script>

@endsection