<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Basic styling for the product page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Fixed Header with Cart Icon */
        header {
            background-color: #333;
            color: white;
            padding: 15px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
        }

        h1 {
            margin: 0;
            flex: 1;
            text-align: center;
        }

        /* Cart Icon Styling */
        .view-cart-btn {
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 24px;
            text-decoration: none;
            margin-right: 20px;
            position: relative;
        }

        /* Badge for Cart Count */
        .view-cart-btn .badge {
            background-color: red;
            color: white;
            font-size: 12px;
            padding: 3px 6px;
            border-radius: 50%;
            position: absolute;
            top: -5px;
            right: -10px;
        }

        /* Add padding to avoid overlap with fixed header */
        .container {
            padding-top: 80px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .product-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 15px;
            width: 250px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .price {
            font-size: 16px;
            font-weight: bold;
            color: #27ae60;
        }

        .add-to-cart-btn {
            background-color: #27ae60;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 10px;
        }

        .add-to-cart-btn:hover {
            background-color: #2d9b55;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 30px;
        }


        .alert-message {
            background-color: #27ae60;
            color: white;
            padding: 10px;
            margin: 100px auto 20px;
            /* Increased top margin */
            width: 80%;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header>
        <h1>Products List</h1>
        <a href="{{ route('cart.index') }}" class="view-cart-btn">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-item" class="badge">
                {{ session('cart') ? count(session('cart')) : 0 }}
            </span>
        </a>
    </header>

    @if (session('success'))
        <div class="alert-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="container">
        @foreach ($products as $product)
            <div class="product-card">
                <img src="{{ $product->image }}" alt="{{ $product->title }}">
                <h2>{{ $product->title }}</h2>
                <p class="price">${{ $product->price }}</p>
                <p class="category"><strong>Category:</strong> {{ $product->category }}</p>
                <p><strong>Description:</strong> {{ $product->description }}</p>
                <p class="rating"><strong>Rating:</strong> {{ json_decode($product->rating)->rate }}
                    ({{ json_decode($product->rating)->count }} reviews)
                </p>

                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                </form>
            </div>
        @endforeach
    </div>

    <footer>
        <p>&copy; 2025 Your Company</p>
    </footer>

</body>

</html>
