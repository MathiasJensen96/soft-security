<?php include __DIR__ . "/../db/UserDao.php";

session_start();

$password = $_POST['password'];
$userDao = new UserDao();
$user = $userDao->getUser($_POST['email']);
if ($user && password_verify($password, $user->password)) {
    session_regenerate_id();
    $_SESSION['email'] = $user->email;
    $_SESSION['role'] = $user->role;

    echo "<h1>Welcome</h1>";
} else {
    echo 'Incorrect username or password.';
}
