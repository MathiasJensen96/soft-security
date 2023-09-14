<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="views/style.css" rel="stylesheet" />
        <title> Signup Page </title>
    </head>
    <body>
        <div style="text-align: center;">
            <h1> Signup here </h1>
        </div>
        <div class="container">
            <form method="POST" action="/register">
                <label>
                    Email:
                    <input type="email" placeholder="Enter Email" name="email" required>
                </label>
                <label>
                    Password:
                    <input type="password" placeholder="Enter Password" name="password" required>
                </label>
                <button type="submit">Signup</button>
            </form>
        </div>
    </body>
</html>