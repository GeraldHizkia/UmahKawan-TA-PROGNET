<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <!-- KIRI -->
        <a class="navbar-brand" href="index.php">
            Umah<small>Kawan</small>
        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- KANAN -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a href="index.php" class="nav-link active">Home</a>
                </li>

                <li class="nav-item">
                    <a href="menu.php" class="nav-link">Menu</a>
                </li>

                <li class="nav-item">
                    <a href="services.php" class="nav-link">Review</a>
                </li>

                <li class="nav-item">
                    <a href="about.php" class="nav-link">About</a>
                </li>

                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                        id="dropdownShop"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Shop
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="shop.php">Shop</a></li>
                        <li><a class="dropdown-item" href="produk.php">Single Product</a></li>
                        <li><a class="dropdown-item" href="checkout.php">Checkout</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="contact.php" class="nav-link">Contact</a>
                </li>

                <!-- Cart -->
                <li class="nav-item">
                    <a href="cart.php" class="nav-link">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<script src="https://kit.fontawesome.com/66f1b6c083.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>