<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{



    public function fetchProducts()
    {
        // API se data fetch karna
        $response = Http::get('https://fakestoreapi.com/products');
        $products = $response->json();

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['product_unique_id' => $product['id']], // Unique ID
                [
                    'title' => $product['title'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'category' => $product['category'],
                    'rating' => json_encode($product['rating']), // Rating ko JSON format mein store karna
                ]
            );
        }

        return redirect()->route('products.index');
    }


    public function index()
    {
        // Products ko fetch karna
        $products = Product::all();
        return view('products.index', compact('products'));
    }
    public function addToCart($id)
    {
        $product = Product::find($id);

        if (!session()->has('cart')) {
            session()->put('cart', []);
        }

        $cart = session()->get('cart');
        $cart[$id] = [
            'product' => $product,
            'quantity' => isset($cart[$id]) ? $cart[$id]['quantity'] + 1 : 1,
        ];

        session()->put('cart', $cart);

        return redirect()->route('products.index')->with('success', 'Product added to cart!');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function removeItem($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

}
