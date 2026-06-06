@extends('admin.layout')
@section('title','Drink Order')
@section('content')
@include('admin.css.kitchen')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    .kitchen-item.flagged-item {
        color: red;
        font-weight: bold;
    }
</style>
<div class="kitchen-wrapper">
    <!-- NEW ORDERS -->
    <div class="kitchen-column">
        <div class="column-header new">
            NEW ORDERS
            <span class="order-count" id="new-count">0</span>
        </div>
        <div class="search-wrapper">
            <input type="text" class="order-search" data-target="#pending-orders" placeholder="Search New Orders...">
        </div>
        <div id="pending-orders" class="order-list">

        </div>
    </div>
    <!-- READY -->
    <div class="kitchen-column">
        <div class="column-header ready">
            READY
            <span class="order-count" id="ready-count">0</span>
        </div>
        <div class="search-wrapper">
            <input type="text" class="order-search" data-target="#ready-orders" placeholder="Search Ready Orders...">
        </div>
        <div id="ready-orders" class="order-list">
        </div>
    </div>
</div>

@endsection