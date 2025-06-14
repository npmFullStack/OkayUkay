<link href="./assets/css/sidebar.css" rel="stylesheet" />

<nav class="sidebar">
    <div class="user-section">
        <i class="fas fa-user-circle"> </i>
        <div>
                  <?php if (
                   isset($_SESSION["firstname"]) &&
                   isset($_SESSION["lastname"])
                  ): ?>
            <span class="first-name"><?= $_SESSION["firstname"] ?></span>
            <span class="last-name"><?= $_SESSION["lastname"] ?></span>
            <?php else: ?>
            <span class="firstname">Guest</span>
            <?php endif; ?>
        </div>
    </div>
    <ul class="nav-list">
        <li>
            <a href="index.php"> <i class="fas fa-home"></i>Home</a>
        </li>
        <?php if (isset($_SESSION["user_id"])): ?>
        <li>
            <a href="dashboard.php"> <i class="fas fa-dashboard"></i>Dashboard</a>
        </li>
        <li>
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i>Cart</a>
        </li>
        <li>
            <a href="product.php"> <i class="fas fa-shopping-bag"></i>Product</a>
        </li>
        <li>
            <a href="logout.php"> <i class="fas fa-sign-out"></i>Logout</a>
        </li>
        <?php else: ?>
        <li>
            <a href="login.php"> <i class="fas fa-sign-in"></i>Login</a>
        </li>
        <?php endif; ?>
    </ul>

    <footer class="footer">
        <div class="logo">OKAY <span class="ukayText">UKAY </span></div>
        <p>All rights reserved.</p>
    </footer>
</nav>

<script src="./assets/js/sidebar.js"></script>
