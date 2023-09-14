<div class="topnav">
    <ul class="nav-left">
        <li><a href="/">Home</a></li>
        <li><a href="products-page">Products</a></li><?php
        if(!empty($_SESSION['authenticated'])) {
            echo '<li><a href="orders-page">Orders</a></li>';
        }
        ?>
        <?php
        if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            echo '<li><a href="admin-page">Admin</a></li>';
        }
        ?>
    </ul>
    <ul class="nav-right">
        <?php
        if(!empty($_SESSION['authenticated'])) {
            echo '<li>' . $_SESSION['email'] . ' (' . $_SESSION['role'] . ')</li>';
            echo '<li><a href="/logout">Logout</a></li>';
        } else {
            echo '<li><a href="/login">Login</a></li>';
            echo '<li><a href="/register">Register</a></li>';
        }
        ?>
    </ul>
</div>