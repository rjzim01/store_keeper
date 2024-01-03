@extends('dekho.base')

@section('content')

{{-- <h1>Sale Transaction History</h1> --}}
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Sale Transaction History</h1>
    {{-- <a href="{{ route('products.add') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Add </a> --}}
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            {{-- <th>ID</th> --}}
            <th>Id</th>
            <th>Name</th>
            {{-- <th>In Stock</th> --}}
            {{-- <th>Price</th> --}}
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @php
            $serial = 0;
            $totalPrice = 0;
            $totalSellProduct = 0;
        @endphp
        @foreach($transactions as $transaction)
        @php
            $serial++;
            $transactionPrice = $transaction->price * $transaction->sellProduct;
            $totalPrice += $transactionPrice;
            $totalSellProduct += $transaction->sellProduct;
        @endphp
        <tr>
            <td>{{ $transaction->id }}</td>
            {{-- <td>{{ $serial }}</td> --}}
            <td>{{ $transaction->name }}</td>
            {{-- <td>{{ $transaction->quantity - $transaction->sellProduct }}</td> --}}
            {{-- <td>${{ $transaction->price }}</td> --}}
            <td>{{ $transaction->sellProduct }}</td>
            <td>${{ $transaction->price * $transaction->sellProduct }}</td>
            {{-- <td>{{ $transaction->created_at }}</td> --}}
            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A,  d-F-Y') }}</td>
        </tr>
        @endforeach

        
        
        {{-- <tr>
            <td colspan="1">Sub Total</td>
            <td>{{ $totalSellProduct }}</td>
            <td>Sub Total Price</td>
            <td>${{ $totalPrice }}</td>
        </tr> --}}
    </tbody>
</table>
<div class="d-flex justify-content-end mt-3">
    {{ $transactions->links() }}
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Total Quantity</th>
            <th>{{ $totalSellProducts[0]->total_sell_products }}</th>
            <th>Total Price</th>
            <th>${{ $totalSellAmount[0]->total_sell_amount }}</th>
        </tr>
    </thead>
</table>


@endsection
{{-- {{ $transactions->links() }} --}}
{{-- <div class="d-flex justify-content-end mt-3">
    {{ $transactions->links() }}
</div> --}}