<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
        }

        .cart-item {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
            margin: 15px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-item img {
            width: 120px;
            height: auto;
            border-radius: 8px;
            margin-right: 20px;
        }

        .cart-item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .cart-item-details h2 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }

        .price {
            color: #27ae60;
            font-weight: bold;
            font-size: 16px;
        }

        .category {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .quantity-section {
            display: flex;
            align-items: center;
        }

        .quantity-input {
            width: 50px;
            padding: 5px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .update-quantity-btn,
        .remove-btn {
            background-color: #27ae60;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .update-quantity-btn:hover,
        .remove-btn:hover {
            background-color: #2d9b55;
        }

        .cart-total {
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
            text-align: left;
            margin-top: 30px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .cart-footer {
            width: 90%;
            max-width: 900px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-footer a {
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }

        .cart-footer a:hover {
            background-color: #45a049;
        }

        .empty-cart-message {
            font-size: 18px;
            color: #e74c3c;
            font-weight: bold;
            text-align: center;
            margin-top: 50px;
        }

        .alert-message {
            background-color: #27ae60;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }

        .continue-shopping-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
        }

        .continue-shopping-btn:hover {
            background-color: #3498db;
        }
    </style>
</head>

<body>

    <header>
        <h1>Shopping Cart</h1>
    </header>

    @if (session('success'))
        <div class="alert-message">{{ session('success') }}</div>
    @endif

    <div class="container">
        @if (session()->has('cart') && count(session('cart')) > 0)
            @foreach (session('cart') as $id => $item)
                <div class="cart-item">
                    <img src="{{ $item['product']->image }}" alt="{{ $item['product']->title }}">
                    <div class="cart-item-details">
                        <h2>{{ $item['product']->title }}</h2>
                        <p class="price">${{ $item['product']->price }}</p>
                        <p class="category">Category: {{ $item['product']->category }}</p>

                        <div class="quantity-section">
                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                    class="quantity-input">
                                <button type="submit" class="update-quantity-btn">Update</button>
                            </form>

                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="cart-total">
                <p>Total:
                    ${{ $total = collect(session('cart'))->sum(function ($item) {
                        return $item['product']->price * $item['quantity'];
                    }) }}
                </p>
            </div>

            <div class="cart-footer">
                <a href="{{ route('checkout') }}">Proceed to Checkout</a>
                <a href="{{ route('products.index') }}">Continue Shopping</a>
            </div>
        @else
            <p class="empty-cart-message">Your cart is empty!</p>
        @endif
    </div>


</body>

</html>
