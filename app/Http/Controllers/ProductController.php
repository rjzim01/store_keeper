<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SellProduct;
use App\Models\UpdateProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function showAddProduct()
    {
        $products = DB::table('products')
            ->select(
                'products.*',
                DB::raw('COALESCE(SUM(sell_products.sellProduct), 0) as total_sell_count'),
                DB::raw('COALESCE(products.quantity - SUM(sell_products.sellProduct), products.quantity) as available_quantity')
            )
            ->leftJoin('sell_products', 'products.id', '=', 'sell_products.product_id')
            ->groupBy('products.id', 'products.name', 'products.quantity', 'products.price', 'products.created_at', 'products.updated_at')
            ->get();


        //return view('show', compact('products'));
        return view('show2', compact('products'));
    }

    public function showAddProductForm()
    {
        //return view('add');
        return view('add2');
    }

    public function addProduct(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        //Product::create($data);

        return redirect()->route('products.show')->with('success', 'Product added successfully!');
        //return $data;
    }

    public function updateProduct(Product $product)
    {
        //return view('update', ['product' => $product]);
        //return $product;
        $totalQuantity = $product->quantity;

        // Assuming you have a relationship between products and sell_products
        $soldQuantity = $product->sellProducts()->sum('sellProduct');

        $availableQuantity = $totalQuantity - $soldQuantity;

        //return ['availableQuantity' => $availableQuantity, 'product' => $product];
        // return [
        //     'totalQuantitySold' => $totalQuantitySold,
        //     'totalPriceSold' => $totalPriceSold,
        // ];
        // $productDetails = [
        //     'id' => $product->id,
        //     'name' => $product->name,
        //     'availableQuantity' => $availableQuantity,
        // ];

        //return $productDetails;
        //return view('update', $productDetails);
        return view('update', ['product' => $product, 'availableQuantity' => $availableQuantity]);
        //return ['product' => $product, 'availableQuantity' => $availableQuantity];
    }

    // public function updateProductStore(Product $product, Request $request)
    // {
    //     $product->update([
    //         'name' => $request->input('name'),
    //         'quantity' => $request->input('quantity'),
    //         'price' => $request->input('price'),
    //     ]);
    //     return redirect()->route('products.show')->with('success', 'Product updated successfully!');
    // }
    public function updateProductStore(Product $product, Request $request)
    {
        // Retrieve the current available quantity
        $totalQuantity = $product->quantity;                                     // 7
        $soldQuantity = $product->sellProducts()->sum('sellProduct');            // 5
        $availableQuantity = $totalQuantity - $soldQuantity;                     // 2 = 7 - 5                       

        // Get the requested new quantity from the form                          // >               // <            // =
        $newQuantity = $request->input('quantity');                              // 3               // 1            // 2
        $quantityDifference = $newQuantity - $availableQuantity;                 // 1 = 3 - 2       // -1 = 1 - 2   // 0 = 2 - 2

        // Check if the requested quantity is greater than the available quantity
        if ($newQuantity > $availableQuantity) {
            // Calculate the difference between the new quantity and available quantity
            //$quantityDifference = $newQuantity - $availableQuantity;

            // Update the product's quantity by adding the difference to the total quantity
            $product->update([
                'name' => $request->input('name'),
                'quantity' => $totalQuantity + $quantityDifference,               // 8 => 7 + 1   
                'price' => $request->input('price'),
            ]);

            // added
            UpdateProduct::create(
                [
                    'product_id' => $product->id,
                    'updateQuantity' => $quantityDifference,                      // 1
                    'updatePrice' => $request->input('price'),
                ]
            );

            return redirect()->route('products.show')->with('success', 'Product updated successfully!');
        }

        if ($newQuantity == $availableQuantity) {
            // Update the product attributes without changing the quantity
            $product->update([
                'name' => $request->input('name'),
                'quantity' => $totalQuantity + $quantityDifference,                // 7 => 7 + 0
                'price' => $request->input('price'),
            ]);

            // added
            UpdateProduct::create(
                [
                    'product_id' => $product->id,
                    'updateQuantity' => $quantityDifference,                       // 0
                    'updatePrice' => $request->input('price'),
                ]
            );

            return redirect()->route('products.show')->with('success', 'Product updated successfully!');
        }

        // Update the product attributes without changing the quantity
        $product->update([
            'name' => $request->input('name'),
            'quantity' => $totalQuantity + $quantityDifference,                    // 6 => 7 + (-1)
            'price' => $request->input('price'),
        ]);

        // added
        UpdateProduct::create(
            [
                'product_id' => $product->id,
                'updateQuantity' => $newQuantity - $availableQuantity,              // -1
                'updatePrice' => $request->input('price'),
            ]
        );

        return redirect()->route('products.show')->with('success', 'Product updated successfully!');
    }


    public function sellProduct($productId)
    {
        $product = Product::findOrFail($productId);

        $productDetails = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                'products.quantity',
                DB::raw('COALESCE(products.quantity - SUM(sell_products.sellProduct), products.quantity) as available_quantity')
            )
            ->leftJoin('sell_products', 'products.id', '=', 'sell_products.product_id')
            ->where('products.id', $productId)
            ->groupBy('products.id', 'products.name', 'products.quantity')
            ->first();

        return view('sell', compact('product', 'productDetails'));
        //return $productDetails;
    }

    public function sellProductStore(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'sellProduct' => 'required|integer',
        ]);
        SellProduct::create($data);
        return redirect()->route('transactions')->with('success', 'Product added successfully!');
    }

    public function showDashboard()
    {
        $todaySales = $this->getSalesForDate(Carbon::today());
        $yesterdaySales = $this->getSalesForDate(Carbon::yesterday());
        $thisMonthSales = $this->getSalesForMonth(Carbon::now()->month);
        $lastMonthSales = $this->getSalesForMonth(Carbon::now()->subMonth()->month);

        //return view('dashboard', compact('todaySales', 'yesterdaySales', 'thisMonthSales', 'lastMonthSales'));
        return view('dekho.index', compact('todaySales', 'yesterdaySales', 'thisMonthSales', 'lastMonthSales'));
    }

    private function getSalesForDate($date)
    {
        $totalQuantitySold = SellProduct::whereDate('created_at', $date)->sum('sellProduct');

        $totalPriceSold = DB::table('products')
            ->join('sell_products', 'products.id', '=', 'sell_products.product_id')
            ->whereDate('sell_products.created_at', $date)
            ->sum(DB::raw('products.price * sell_products.sellProduct'));

        return [
            'totalQuantitySold' => $totalQuantitySold,
            'totalPriceSold' => $totalPriceSold,
        ];
    }

    private function getSalesForMonth($month)
    {
        $totalQuantitySold = SellProduct::whereMonth('created_at', $month)->sum('sellProduct');
        $totalPriceSold = DB::table('products')
            ->join('sell_products', 'products.id', '=', 'sell_products.product_id')
            ->whereMonth('sell_products.created_at', $month)
            ->sum(DB::raw('products.price * sell_products.sellProduct'));

        return [
            'totalQuantitySold' => $totalQuantitySold,
            'totalPriceSold' => $totalPriceSold,
        ];
    }

    public function showTransactionHistory()
    {
        // $transactions = DB::table('products')
        $transactions = DB::table('sell_products')
            // //->join('sell_products', 'products.id', '=', 'sell_products.product_id')
            // ->join('products', 'sell_products.product_id', '=', 'products.id')
            // ->orderBy('sell_products.id', 'asc')
            // ->get();
            // //->paginate(7);

            // ->select('sell_products.id as id', 'sell_products.product_id', 'sell_products.sellProduct', 'sell_products.created_at', 'sell_products.updated_at', 'products.name', 'products.quantity', 'products.price')
            // ->join('products', 'sell_products.product_id', '=', 'products.id')
            // ->orderBy('sell_products.id', 'desc')
            // ->get();

            //---3

            // ->select(
            //     'sell_products.id as id',
            //     'sell_products.product_id',
            //     'sell_products.sellProduct',
            //     'sell_products.created_at',
            //     'sell_products.updated_at',
            //     'products.name',
            //     'products.quantity',
            //     'products.price',
            //     DB::raw('SUM(sell_products.sellProduct) as total_sell_products'),
            //     DB::raw('SUM(sell_products.sellProduct * products.price) as total_sell_amount')
            // )
            // ->join('products', 'sell_products.product_id', '=', 'products.id')
            // ->groupBy('sell_products.id', 'sell_products.product_id', 'sell_products.sellProduct', 'sell_products.created_at', 'sell_products.updated_at', 'products.name', 'products.quantity', 'products.price')
            // ->orderBy('sell_products.id', 'asc')
            // ->get();

            ->select(
                'sell_products.id as id',
                'sell_products.product_id',
                'sell_products.sellProduct',
                'sell_products.created_at',
                'sell_products.updated_at',
                'products.name',
                'products.quantity',
                'products.price'
            )
            ->join('products', 'sell_products.product_id', '=', 'products.id')
            ->orderBy('sell_products.id', 'desc')
            ->paginate(7);

        $totalSellProducts = DB::table('sell_products')
            ->select(DB::raw('SUM(sellProduct) as total_sell_products'))
            ->get();

        $totalSellAmount = DB::table('sell_products')
            ->join('products', 'sell_products.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(sellProduct * products.price) as total_sell_amount'))
            ->get();

        //return view('transactions2', compact('transactions'));
        //return $transactions;
        return view('transactions2', compact('transactions', 'totalSellProducts', 'totalSellAmount'));
        // return [
        //     'transactions' => $transactions,
        //     'totalSellProducts' => $totalSellProducts[0]->total_sell_products,
        //     'totalSellAmount' => $totalSellAmount[0]->total_sell_amount,
        // ];

    }


}
