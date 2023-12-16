@extends('0_layout')

@section('content')

<h1>Add a New Product</h1>
<form method="POST" action="{{ route('products.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Product Name">
    <br>
    <input type="number" name="quantity" placeholder="Quantity">
    <br>
    <input type="number" step="0.01" name="price" placeholder="Price">
    <br>
    <button type="submit">Add Product</button>
</form>

@endsection
