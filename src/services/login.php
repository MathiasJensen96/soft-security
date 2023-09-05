<?php include __DIR__ . "/../db/UserDao.php";

session_start();

if (empty($_POST['password'])) {
    return http_response_code(400);
}
$password = $_POST['password'];

$userDao = new UserDao();

$user = $userDao->getUser($_POST['email']);
if ($user && password_verify($password, $user->getPassword())) {
    session_regenerate_id();
    $_SESSION['role'] = $user->getRole();

    echo "<h1>Welcome</h1>";
} else {
    echo 'Incorrect username or password.';
}
