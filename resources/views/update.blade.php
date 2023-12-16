@extends('0_layout')

@section('content')

<h1>Update Product</h1>
<form method="POST" action="{{ route('products.updateStore', ['product' => $product]) }}">
    @csrf
    @method('put')
    <input type="text" name="name" placeholder="Product Name" value="{{$product->name}}">
    <br>
    <input type="number" name="quantity" placeholder="Quantity" value="{{$product->quantity}}">
    <br>
    <input type="number" step="0.01" name="price" placeholder="Price" value="{{$product->price}}">
    <br>
    <button type="submit">Update Product</button>
</form>

@endsection
