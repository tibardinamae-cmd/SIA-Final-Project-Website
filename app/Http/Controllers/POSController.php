<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->where('quantity', '>', 0)->get();
        $categories = Category::all();
        return view('pos.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'payment' => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $total = 0;
                foreach ($request->cart as $item) {
                    $product = Product::findOrFail($item['id']);
                    if ($product->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }
                    $total += $product->price * $item['quantity'];
                }

                if ($request->payment < $total) {
                    throw new \Exception("Insufficient payment amount.");
                }

                $transaction = Transaction::create([
                    'total_amount' => $total,
                    'payment' => $request->payment,
                    'change' => $request->payment - $total,
                    'cashier_id' => auth()->id(),
                ]);

                foreach ($request->cart as $item) {
                    $product = Product::findOrFail($item['id']);
                    
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'subtotal' => $product->price * $item['quantity'],
                    ]);

                    $product->decrement('quantity', $item['quantity']);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction completed successfully',
                    'data' => [
                        'id' => $transaction->id,
                        'total' => $transaction->total_amount,
                        'payment' => $transaction->payment,
                        'change' => $transaction->change,
                        'items' => $transaction->details->map(function($detail) {
                            return [
                                'name' => $detail->product->name,
                                'qty' => $detail->quantity,
                                'subtotal' => $detail->subtotal
                            ];
                        })
                    ]
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
