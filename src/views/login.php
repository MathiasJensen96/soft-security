<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="views/style.css" rel="stylesheet" />
        <title> Login Page </title>
    </head>
    <body>
        <div class="topnav">
            <div class="nav-left">
                <a href="/">Home</a>
                <a href="products">Products</a>
                <a href="orders">Orders</a>
            </div>
            <div class="nav-right">
                <a class="active" href="/login">Login</a>
                <a href="/register">Register</a>
            </div>
        </div>
        <div style="text-align: center;"> <h1> Login here </h1> </div>
        <div class="container">
            <form method="POST" action="/login">
                <label>
                    Email:
                    <input type="email" placeholder="Enter Email" name="email" required>
                </label>
                <label>
                    Password:
                    <input type="password" placeholder="Enter Password" name="password" required>
                </label>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
</html>