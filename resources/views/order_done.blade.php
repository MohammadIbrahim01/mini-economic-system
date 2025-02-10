<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            color: red;
            font-size: 36px;
        }

        h2 {
            color: green;
            font-size: 24px;
        }

        .item-box {
            background-color: #ff4d4d;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 0;
            font-size: 18px;
        }

        .details {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
        }

        .highlight {
            font-weight: bold;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <h1>Thank You!</h1>
    <h2>
        @if ($message = Session::get('success'))
            <strong>{{ $message }}</strong>
        @endif

        @if ($message = Session::get('error'))
            <strong>{{ $message }}</strong>
        @endif
    </h2>

    <div class="item-box">
        Items Purchased:
        @foreach (session('products', []) as $product)
            {{ $product['name'] }} ({{ $product['quantity'] }}),
        @endforeach
    </div>

    <div class="details">
        <p>Your Name: <span class="highlight">{{ session('name') }}</span></p>
        <p>Your E-mail: <span class="highlight">{{ session('email') }}</span></p>
        <p>Total Amount Paid: <span class="highlight">{{ number_format(session('total_amount', 0), 2) }}</span></p>
    </div>

    <a href="{{ route('products.index') }}" class="button">Continue Shopping</a>

</body>

</html>
