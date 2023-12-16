@extends('0_layout')

@section('content')

<h1>Sale Transaction History</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            {{-- <th>ID</th> --}}
            <th>Name</th>
            {{-- <th>In Stock</th> --}}
            {{-- <th>Price</th> --}}
            <th>Sell Product</th>
            <th>Total Price</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalPrice = 0;
            $totalSellProduct = 0;
        @endphp
        @foreach($transactions as $transaction)
        @php
            $transactionPrice = $transaction->price * $transaction->sellProduct;
            $totalPrice += $transactionPrice;
            $totalSellProduct += $transaction->sellProduct;
        @endphp
        <tr>
            {{-- <td>{{ $transaction->id }}</td> --}}
            <td>{{ $transaction->name }}</td>
            {{-- <td>{{ $transaction->quantity - $transaction->sellProduct }}</td> --}}
            {{-- <td>${{ $transaction->price }}</td> --}}
            <td>{{ $transaction->sellProduct }}</td>
            <td>${{ $transaction->price * $transaction->sellProduct }}</td>
            <td>{{ $transaction->created_at }}</td>
        </tr>
        @endforeach
        
        <tr>
            <td colspan="1">Sub Total</td>
            <td>{{ $totalSellProduct }}</td>
            <td>Sub Total Price</td>
            <td>${{ $totalPrice }}</td>
        </tr>
    </tbody>
</table>

@endsection