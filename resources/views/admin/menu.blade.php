@extends('admin.layout')
@section('title','Admin Menu')

@section('top')
<span>
    @if(session()->has('message'))
    {{session()->get('message')}}
    @endif
    <p id="menuMessage" style="color:green;margin-bottom:-10px"></p>
</span>
<div class="block-body text-right">
    <a href="{{route('admin.menu_ingredient')}}" class="btn btn-secondary" style="color:white">Ingredient</a>
    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add Menu</button>
    <!-- Modal-->
    <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Add Menu</strong>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                    <form action="{{url('create_menu')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-control-label" style="color:white">Menu Name</label>
                            <input type="text" name="menu_name" placeholder="Menu Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" style="color:white">Price</label>
                            <input type="text" name="menu_price" placeholder="Menu Price" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" style="color:white">Index No</label>
                            <input type="text" name="menu_no" placeholder="Index No" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" style="color:white">Prep. Time</label>
                            <input type="text" name="prep_time" placeholder="Preparation Time" class="form-control">
                        </div>
                        <label class="label-material" style="color:white">Select</label>
                        <div class="col-sm-13">
                            <select name="role" style="color:white" class="form-control mb-8 mb-8" validate>
                                @foreach($categories as $menu)
                                <option style="background-color: #2f2f2fff" value="{{$menu->id}}">{{$menu->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="color:white">
                            <label></label>
                            <div>
                                <input id="optionsRadios1" type="radio" value="1" name="to_make"><label for="optionsRadios1">To Kichen</label>
                            </div>
                            <div>
                                <input id="optionsRadios1" type="radio" value="2" name="to_make"><label for="optionsRadios1">To Store</label>
                            </div>
                            <div>
                                <input id="optionsRadios1" type="radio" value="3" name="to_make"><label for="optionsRadios1">To Other</label>
                            </div>
                            <div>
                                <input id="optionsRadios1" type="radio" value="4" name="to_make"><label for="optionsRadios1">Too Other</label>
                            </div>
                        </div>
                        <div class="form-group">

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    <input type="submit" value="Create Menu" class="btn btn-primary">

                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')

@include('admin.css.order-system-styles')

<div class="order-system-wrapper">
    <div class="order-three-column">

        <!-- {{-- LEFT COLUMN – CATEGORIES --}} -->
        <div class="categories-card">
            <div class="categories-header" style="color:white">
                <i class="icon-list"></i> Categories
            </div>

            <div class="category-items">
                @foreach($cattt as $cat)
                <button class="category-btn"
                    data-cat-id="{{ $cat->id }}"
                    data-cat-type="{{ $cat->type }}">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- MIDDLE COLUMN – MENUS --}}
        <div>
            <div class="menu-grid" id="menuGrid"></div>

        </div>

        {{-- RIGHT COLUMN – EDIT MENU --}}
        <div class="order-card" style="color:white">
            <div class="order-content">
                <h5 class="order-title">Edit Menu</h5>

                <div class="block-body" style="color:white">
                    <form id="editMenuForm">
                        @csrf
                        <input type="hidden" id="menu_id">

                        <div class="form-group" style="color:white">
                            <label style="color:white">Menu Name</label>
                            <input style="color:white" type="text" id="menu_name"  class="form-control">
                        </div>

                        <div class="form-group" style="color:white">
                            <label style="color:white">Price</label>
                            <input type="number" id="menu_price" style="color:white" class="form-control">
                        </div>

                        <div class="form-group" style="color:white">
                            <label style="color:white">Index No</label>
                            <input type="text" id="menu_index_no" style="color:white" class="form-control">
                        </div>
                        <div id="prepTimeGroup" class="form-group" style="color:white">
                            <label style="color:white">Prep. Time</label>
                            <input type="text" id="menu_prep" style="color:white" class="form-control">
                        </div>
                        <div class="form-group" style="color:white">
                            <div>
                                <input id="to_make_1" type="radio" value="1" name="edit_to_make">
                                <label style="color:white" for="to_make_1">To Kitchen</label>
                            </div>
                            <div>
                                <input id="to_make_2" type="radio" value="2" name="edit_to_make">
                                <label style="color:white" for="to_make_2">To Store</label>
                            </div>
                            <div>
                                <input id="to_make_3" type="radio" value="3" name="edit_to_make">
                                <label style="color:white" for="to_make_3">To Other</label>
                            </div>
                            <div>
                                <input id="to_make_4" type="radio" value="4" name="edit_to_make">
                                <label style="color:white" for="to_make_4">Too Other</label>
                            </div>
                        </div>

                        <!-- Wrap buttons in a container and hide by default -->
                        <div id="menuActionButtons" style="display:none; margin-top:10px;">
                            <button type="button" id="deleteMenuBtn" class="btn btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                            <button type="button" id="cancelMenuBtn" class="btn btn-secondary">
                                X
                            </button>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary text-right">
                                Update Menu
                            </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const categoryButtons = document.querySelectorAll('.category-btn');
        const menuMessage = document.getElementById('menuMessage'); // Div for showing messages
        const actionButtons = document.getElementById('menuActionButtons');

        // ===============================
        // Attach click events to categories
        // ===============================
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const catId = this.dataset.catId;
                const catType = this.dataset.catType; // we’ll add this

                loadMenusOrDrinks(catId, catType);
            });
        });

        // Auto-load first category
        // Auto-load first category (food or drink)
        if (categoryButtons.length > 0) {
            const firstBtn = categoryButtons[0];
            firstBtn.classList.add('active');

            const firstCatId = firstBtn.dataset.catId;
            const firstCatType = firstBtn.dataset.catType; // get type

            loadMenusOrDrinks(firstCatId, firstCatType);
        }

        // ===============================
        // Load menus by category
        // ===============================
        function loadMenusOrDrinks(catId, type) {
            const menuGrid = document.getElementById('menuGrid');
            let url = '';

            if (type.toLowerCase() === 'food') {
                url = `/menus/category/${catId}`;
            } else if (type.toLowerCase() === 'drink') {
                url = `/drinks/category/${catId}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(items => {
                    let html = '';

                    if (items.length === 0) {
                        html = '<p style="color:#aaa">No items found</p>';
                    } else {
                        items.forEach(item => {

                            // truncate name like before
                            let shortName = item.name.length > 15 ?
                                item.name.substring(0, 15) + '...' :
                                item.name;

                            // badge only for food
                            let badge = '';
                            if (item.index_no) {
                                badge = `<span class="menu-badge">${item.index_no}</span>`;
                            }

                            html += `
                                <div class="menu-card"
                                    onclick="editItem(${item.id}, '${type}')"
                                    style="position: relative;">

                                    ${badge}

                                    <div class="menu-name">${shortName}</div>
                                    <div class="menu-price">Br ${item.price}</div>
                                </div>
                            `;
                        });
                    }

                    menuGrid.innerHTML = html;
                })
                .catch(err => console.error(err));
        }

        // ===============================
        // Load menu into edit form
        // ===============================
        window.editItem = function(id, type) {

            let url = '';

            if (type.toLowerCase() === 'food') {
                url = `/menu/${id}`;
            } else {
                url = `/drink/${id}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {

                    document.getElementById('menu_id').value = data.id;
                    document.getElementById('menu_name').value = data.name;
                    document.getElementById('menu_price').value = data.price;
                    if (type.toLowerCase() === 'food') {
                        document.getElementById('menu_prep').value = data.prep_time ?? '';
                    }

                    document.getElementById('menu_index_no').closest('.form-group').style.display = 'block';
                    document.querySelectorAll('input[name="edit_to_make"]').forEach(r => {
                        r.closest('.form-group').style.display = 'block';
                    });

                    document.getElementById('menu_index_no').value = data.index_no || '';

                    document.getElementById('to_make_1').checked = false;
                    document.getElementById('to_make_2').checked = false;
                    document.getElementById('to_make_3').checked = false;
                    document.getElementById('to_make_4').checked = false;

                    if (data.to_make == 1) {
                        document.getElementById('to_make_1').checked = true;
                    } else if (data.to_make == 2) {
                        document.getElementById('to_make_2').checked = true;
                    } else if (data.to_make == 3) {
                        document.getElementById('to_make_3').checked = true;
                    } else if (data.to_make == 4) {
                        document.getElementById('to_make_4').checked = true;
                    }


                    document.getElementById('editMenuForm').dataset.type = type;

                    document.getElementById('menuActionButtons').style.display = 'block';
                });
            const prepGroup = document.getElementById('prepTimeGroup');

            if (type.toLowerCase() === 'food') {
                prepGroup.style.display = 'block'; // hide for food
            } else {
                prepGroup.style.display = 'none'; // show for drinks
            }
        };

        // ===============================
        // Update menu via AJAX including to_make
        // ===============================
        document.getElementById('editMenuForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const id = document.getElementById('menu_id').value;
            const activeBtn = document.querySelector('.category-btn.active');
            const currentCatId = activeBtn.dataset.catId;
            const currentType = activeBtn.dataset.catType;

            let url = currentType.toLowerCase() === 'food' ?
                `/menu/update/${id}` :
                `/drink/update/${id}`;

            let bodyData = {
                name: document.getElementById('menu_name').value,
                price: document.getElementById('menu_price').value,
                prep_time: document.getElementById('menu_prep').value,
                index_no: document.getElementById('menu_index_no').value,
                to_make: document.querySelector('input[name="edit_to_make"]:checked')?.value
            };

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(bodyData)
                })
                .then(res => res.json())
                .then(() => {
                    loadMenusOrDrinks(currentCatId, currentType);
                    menuMessage.innerText = 'Updated successfully';
                })
                .catch(err => console.error(err));
        });


        // ===============================
        // DELETE BUTTON
        // ===============================
        document.getElementById('deleteMenuBtn').onclick = function() {

            const id = document.getElementById('menu_id').value;
            if (!id) return;

            fetch(`/menu/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Delete failed');
                    return res.json();
                })
                .then(() => {

                    actionButtons.style.display = 'none';
                    document.getElementById('editMenuForm').reset();

                    const activeBtn = document.querySelector('.category-btn.active');
                    const currentCatId = activeBtn.dataset.catId;
                    const currentType = activeBtn.dataset.catType;

                    loadMenusOrDrinks(currentCatId, currentType);

                    menuMessage.innerText = 'Menu deleted successfully';
                })
                .catch(err => console.error(err));
        };


        // ===============================
        // CANCEL BUTTON
        // ===============================
        document.getElementById('cancelMenuBtn').onclick = function() {

            actionButtons.style.display = 'none';
            document.getElementById('editMenuForm').reset();
            document.getElementById('menu_id').value = '';
            menuMessage.innerText = '';
        };


    });
</script>


@endsection