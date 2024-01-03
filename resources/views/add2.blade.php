@extends('dekho.base')

@section('content')

    {{-- <div class="main"> --}}
        {{-- <h1>Add a New Product</h1> --}}
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Product Add</h1>
            {{-- <a href="{{ route('products.add') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Add </a> --}}
        </div>

        {{-- <form method="POST" action="{{ route('products.store') }}">
            @csrf
            <input type="text" name="name" placeholder="Product Name">
            <br>
            <input type="number" name="quantity" placeholder="Quantity">
            <br>
            <input type="number" step="0.01" name="price" placeholder="Price">
            <br>
            <button type="submit">Add Product</button>
        </form> --}}

        {{-- <div class="dropdown-menu"> --}}
            {{-- <form class="px-4 py-3" method="POST" action="{{ route('products.store') }}"> --}}
            <form class="" method="POST" action="{{ route('products.store') }}">
                @csrf
              <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="Product Name">
              </div>
              <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" placeholder="Quantity">
              </div>
              <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price">
              </div>
              <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
            {{-- <div class="dropdown-divider"></div> --}}
        {{-- </div> --}}
    {{-- </div> --}}

@endsection
