<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SellProduct;
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


        return view('show', compact('products'));
    }

    public function showAddProductForm()
    {
        return view('add');
    }

    public function addProduct(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        Product::create($data);

        return redirect()->route('products.show')->with('success', 'Product added successfully!');
    }

    public function updateProduct(Product $product)
    {
        return view('update', ['product' => $product]);
    }

    public function updateProductStore(Product $product, Request $request)
    {
        $product->update([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
        ]);
        return redirect()->route('products.show')->with('success', 'Product updated successfully!');
    }

    public function sellProduct($productId)
    {
        $product = Product::findOrFail($productId);

        $productDetails = DB::table('products')
            ->select(
                'products.id', 'products.name', 'products.quantity',
                DB::raw('COALESCE(products.quantity - SUM(sell_products.sellProduct), products.quantity) as available_quantity')
            )
            ->leftJoin('sell_products', 'products.id', '=', 'sell_products.product_id')
            ->where('products.id', $productId)
            ->groupBy('products.id', 'products.name', 'products.quantity')
            ->first();

        return view('sell', compact('product', 'productDetails'));
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

        return view('dashboard', compact('todaySales', 'yesterdaySales', 'thisMonthSales', 'lastMonthSales'));
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
        $transactions = DB::table('products')
            ->join('sell_products', 'products.id', '=', 'sell_products.product_id')
            ->get();

        return view('transactions', compact('transactions'));
    }


}
