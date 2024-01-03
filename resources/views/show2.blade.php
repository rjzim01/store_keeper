@extends('dekho.base')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Products</h1>
    {{-- <a href="{{ route('products.add') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Add </a> --}}
    <a href="{{ route('products.add') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm " style="width: 120px;">
        <i class="fas fa-plus-circle fa-sm text-white-50" style="margin-right: 10px;"></i> Add
    </a>   
</div>

{{-- <h1>All Available Products</h1>
<p><a href="{{ route('products.add') }}" class="btn btn-primary">Add</a></p>
<button type="button" class="btn">Base class</button> --}}

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
            <td>
                <a href="{{ route('products.sell', ['product' => $product->id]) }}" class="btn btn-primary">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </td>
            <td>
                <a href="{{ route('products.update', ['product' => $product->id]) }}" class="btn btn-success">
                    <i class="fas fa-edit"></i>
                </a>
            </td>
            
        </tr>
        @endforeach
    </tbody>
</table>
</div>

@endsection
