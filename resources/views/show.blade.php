@extends('0_layout')

@section('content')

<h1>All Available Products</h1>
<p><a href="{{ route('products.add') }}" class="btn btn-primary">Add</a></p>
{{-- <button type="button" class="btn">Base class</button> --}}

<div class="main">

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            {{-- <th>Quantity</th> --}}
            <th>Available</th>
            <th>SoldOut</th>
            <th>Price</th>
            <th>Sell</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            {{-- <td>{{ $product->quantity }}</td> --}}
            <td>{{ $product->available_quantity }}</td>
            <td>{{ $product->total_sell_count }}</td>
            <td>${{ $product->price }}</td>
            <td><a href="{{ route('products.sell', ['product' => $product->id]) }}">Sell</a></td>
            <td><a href="{{ route('products.update', ['product' => $product->id]) }}">Edit</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

@endsection
