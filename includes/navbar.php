<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand font-bold text-2xl" href="<?php echo BASE_URL; ?>">LUX<span class="text-yellow-400">LIGHT</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto items-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>user/products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>user/contact.php">Contact</a>
                </li>
                <?php if (isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link text-yellow-400" href="<?php echo BASE_URL; ?>admin/dashboard.php">Admin Panel</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
