@extends('0_layout')

@section('content')

<h1>Sales Dashboard</h1>

<div class="card">
    <h3>Today's Sales</h3>
    {{-- <p>${{ $todaySales }}</p> --}}
    <p>Quantity = {{ $todaySales['totalQuantitySold'] }}</p>
    <p>Price = ${{ $todaySales['totalPriceSold'] }}</p>
    
</div>

<div class="card">
    <h3>Yesterday's Sales</h3>
    {{-- <p>${{ $yesterdaySales }}</p> --}}
    <p>Quantity = {{ $yesterdaySales['totalQuantitySold'] }}</p>
    <p>Price = ${{ $yesterdaySales['totalPriceSold'] }}</p>
</div>

<div class="card">
    <h3>This Month's Sales</h3>
    {{-- <p>${{ $thisMonthSales }}</p> --}}
    <p>Quantity = {{ $thisMonthSales['totalQuantitySold'] }}</p>
    <p>Price = ${{ $thisMonthSales['totalPriceSold'] }}</p>
</div>

<div class="card">
    <h3>Last Month's Sales</h3>
    {{-- <p>${{ $lastMonthSales }}</p> --}}
    <p>Quantity = {{ $lastMonthSales['totalQuantitySold'] }}</p>
    <p>Price = ${{ $lastMonthSales['totalPriceSold'] }}</p>
</div>

@endsection
