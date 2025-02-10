<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" />
</head>
<style>
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

    /* Title Styling */
    header h1 {
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

    /* Prevent Overlapping with Fixed Header */
    .container {
        padding-top: 80px;
    }
</style>

<body>

    <!-- Navbar -->
    <header>
        <h1>Checkout</h1>
        <a href="{{ route('cart.index') }}" class="view-cart-btn">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-item" class="badge">
                {{ session('cart') ? count(session('cart')) : 0 }}
            </span>
        </a>
    </header>


    <div class="container mt-5">
        <div class="row">

            <!-- Left Side - Checkout Form -->
            <div class="col-md-6">
                <h4 class="text-center text-info p-2">Complete Your Order</h4>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ route('paypal.payment') }}" method="POST">
                    @csrf
                    <input type="hidden" name="productsid"
                        value="@foreach ($cart as $item) {{ $item['product']->id }}, @endforeach">
                    <input type="hidden" name="products"
                        value="@foreach ($cart as $item) {{ $item['product']->title }}, @endforeach">
                    <input type="hidden" name="grand_total" value="{{ $total }}">

                    <input type="hidden" name="product_quantity"
                        value="@foreach ($cart as $item) {{ $item['quantity'] }}, @endforeach">
                    <input type="hidden" name="product_price"
                        value="@foreach ($cart as $item) {{ $item['product']->price }}, @endforeach">

                    <div class="form-group">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name"
                            value="ibrahim" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name"
                            value="khan" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Enter E-Mail"
                            value="ik21@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <textarea name="address" class="form-control" rows="3" placeholder="Enter Delivery Address Here..." required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" value="Place Order" class="btn btn-success btn-block">
                    </div>
                </form>

            </div>

            <!-- Right Side - Cart Summary -->
            <div class="col-md-6">
                <h4 class="text-center text-info p-2">Your Cart</h4>
                <div class="jumbotron p-3">
                    <ul class="list-group">
                        @foreach ($cart as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $item['product']->title }}
                                <span
                                    class="badge badge-primary badge-pill">${{ number_format($item['product']->price, 2) }}
                                    x {{ $item['quantity'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <h5 class="mt-3"><b>Total Amount:</b> ${{ number_format($total, 2) }}</h5>
                </div>
            </div>

        </div>
    </div>

</body>

</html>
