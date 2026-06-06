<div class="page-wrapper d-flex flex-column" style="min-height:100vh;">
    <div class="main-container d-flex flex-grow-1 align-items-stretch">
        <nav id="sidebar">
            <span style="color:#1A56DB; margin-top:10px" class="heading ">Main</span>
            <ul class="list-unstyled">
                <li style="color: #a8a6a6;" class="{{ Route::currentRouteName() == 'admin.home' ? 'active' : ''}}"><a href="{{ route('admin.home')}}"><i class="icon-dashboard"></i>Home </a></li>
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.user' ? 'active' : ''}}"><a href="{{route('admin.user')}}"> <i class="fa fa-user"></i>Users / Employees </a></li>
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.waiter' ? 'active' : ''}}"><a href="{{route('admin.waiter')}}"> <i class="fa fa-users"></i>Waiter </a></li>
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.cat' ? 'active' : ''}}"><a href="{{route('admin.cat')}}"> <i class="icon-padnote"></i>Category</a></li>
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.expense' ? 'active' : ''}}"><a href="{{route('admin.expense')}}"> <i class="icon-padnote"></i>Expense </a></li>
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.menu' ? 'active' : ''}}"><a href="{{route('admin.menu')}}"> <i class="icon-padnote"></i>Menu </a></li>
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.order' ? 'active' : ''}}"><a href="{{route('admin.order')}}"> <i class="icon-padnote"></i>Order </a></li>
                <li style="color: #a8a6a6"><a data-target="#exampledropdownDropdown2" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Orders Folder</a>
                    <ul id="exampledropdownDropdown2" class="collapse list-unstyled ">
                        <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.waiting' ? 'active' : ''}}"><a href="{{route('admin.waiting')}}"> <i class="icon-padnote"></i>Waiting Order </a></li>
                        <li class="{{ Route::currentRouteName() == 'admin.flag' ? 'active' : ''}}"><a href="{{route('admin.flag')}}"><i class="icon-padnote"></i>Flagged Order</a></li>
                        <li class="{{ Route::currentRouteName() == 'admin.orderfinished' ? 'active' : ''}}"><a href="{{route('admin.orderfinished')}}"><i class="icon-padnote"></i>Finish Order</a></li>

                    </ul>
                </li>
                <!-- <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.flag' ? 'active' : ''}}"><a href="{{route('admin.flag')}}"> <i class="icon-padnote"></i>Flag Order </a></li> -->
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.kitchen' ? 'active' : ''}}"><a href="{{route('admin.kitchen')}}"> <i class="icon-padnote"></i>Kitchen Order </a></li>
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.drink' ? 'active' : ''}}"><a href="{{route('admin.drink')}}"> <i class="icon-padnote"></i>Drink Order </a></li>
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.stock_reporrt' ? 'active' : ''}}"><a href="{{route('admin.stock_reporrt')}}"> <i class="icon-padnote"></i>Stock Management </a></li>
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.store' ? 'active' : ''}}"><a href="{{route('admin.store')}}"> <i class="icon-padnote"></i>Item</a></li>
                <!-- <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.unit' ? 'active' : ''}}"><a href="{{route('admin.unit')}}"> <i class="icon-padnote"></i>Unit</a></li> -->
                <!-- <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.su' ? 'active' : ''}}"><a href="{{route('admin.su')}}"> <i class="icon-padnote"></i>Supplier</a></li> -->
                <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.stock' ? 'active' : ''}}"><a href="{{route('admin.stock')}}"> <i class="icon-padnote"></i>Stock Movement</a></li>
                <!-- <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.location' ? 'active' : ''}}"><a href="{{route('admin.location')}}"> <i class="icon-padnote"></i>Location</a></li> -->
                <!-- <li style="color: #a8a6a6" class="{{ Route::currentRouteName() == 'admin.lateOrders' ? 'active' : ''}}"><a href="{{route('admin.lateOrders')}}"> <i class="icon-padnote"></i>Kitchen Controller </a></li> -->

            </ul>
        </nav>