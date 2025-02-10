<?php

namespace App\Http\Controllers;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
class PayPalinController extends Controller
{


    public function checkout()
    {
        // Cart session fetch karna
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $total = collect($cart)->sum(function ($item) {
            return $item['product']->price * $item['quantity'];
        });

        return view('checkout', compact('cart', 'total'));
    }



    private function generateUniqueOrderId()
    {
        return 'ORDER-' . strtoupper(uniqid());
    }


    public function payment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $email = $request->input('email');
        $fullName = "$firstName $lastName";
        $totalAmount = $request->input('grand_total');
        $addressLine1 = $request->input('address');

        $productsId = explode(',', trim($request->input('productsid'), ', '));
        $productsName = explode(',', trim($request->input('products'), ', '));
        $productsQuantity = explode(',', trim($request->input('product_quantity'), ', '));
        $productsPrice = explode(',', trim($request->input('product_price'), ', '));

        $products = [];
        foreach ($productsId as $key => $id) {
            $products[] = [
                'id' => $id,
                'name' => $productsName[$key] ?? 'Unknown Product',
                'quantity' => $productsQuantity[$key] ?? 1,
                'price' => $productsPrice[$key] ?? 100.00
            ];
        }

        // Store details in session for later use
        session([
            'name' => $fullName,
            'email' => $email,
            'total_amount' => $totalAmount,
            'address' => $addressLine1,
            'products' => $products
        ]);

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success'),
                "cancel_url" => route('paypal.payment.cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $totalAmount
                    ],
                    "shipping" => [
                        "name" => [
                            "full_name" => $fullName
                        ],
                        "address" => [
                            "address_line_1" => $addressLine1,
                            "address_line_2" => "",
                            "admin_area_2" => "indore",
                            "admin_area_1" => "indore",
                            "postal_code" => "110006",
                            "country_code" => "IN"
                        ]
                    ]
                ]
            ],
            "payer" => [
                "name" => [
                    "given_name" => $firstName,
                    "surname" => $lastName
                ],
                "address" => [
                    "address_line_1" => $addressLine1,
                    "address_line_2" => "",
                    "admin_area_2" => "indore",
                    "admin_area_1" => "indore",
                    "postal_code" => "110006",
                    "country_code" => "IN"
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()->route('checkout')->with('error', 'Something went wrong.');
        } else {
            return redirect()->route('checkout')->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }



    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $name = session('name', 'Unknown');
            $address = session('address', 'N/A');
            $email = session('email', 'N/A');
            $totalAmount = session('total_amount', '0.00');

            $orderId = $this->generateUniqueOrderId();

            $products = session('products', []);

            // Store order details in the database
            $order = Order::create([
                'order_id' => $orderId,
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'total_amount' => $totalAmount,
                'status' => 'completed'
            ]);

            foreach ($products as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'product_name' => $product['name'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price']
                ]);
            }

            session()->forget(['name', 'address', 'email', 'total_amount', 'order_id', 'products']);
            session()->forget('cart');

            session([
                'order_id' => $orderId,
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'total_amount' => $totalAmount,
                'products' => $products
            ]);

            return redirect()->route('order.done')->with('success', 'Your order has been successfully placed!');
        } else {
            return redirect()->route('checkout')->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }




    public function show($orderId)
    {
        // Fetch order details using the order ID
        $order = Order::with('items')->where('order_id', $orderId)->first();

        // Check if the order exists
        if ($order) {
            return view('order.show', compact('order')); // Pass order complete
        } else {
            return redirect()->route('checkout')->with('error', 'Order not found.');
        }
    }

    public function paymentCancel()
    {
        return redirect()->route('checkout')->with('error', 'Transaction canceled.');
    }



}


