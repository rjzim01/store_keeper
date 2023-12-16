@extends('0_layout')

@section('content')

<h1>Sell Product</h1>
<p>Product   -> {{$product->name}}</p>
<p>Available -> {{$productDetails->available_quantity}}</p>
<p>Price     -> {{$product->price}}</p>
<form method="POST" action="{{ route('products.sellStore', ['product' => $product]) }}">
    @csrf
    <input type="hidden" name="product_id" value="{{$product->id}}">
    <br>
    <input type="number" step="1" name="sellProduct" placeholder="Number">
    <br>
    <button type="submit">Sell Product</button>
</form>

@endsection
