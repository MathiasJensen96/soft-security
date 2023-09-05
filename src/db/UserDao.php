<?php
include $_SERVER['DOCUMENT_ROOT'] . '/entities/users.php';

class UserDao
{
    private $userconn;
    private $adminconn;

    function __construct()
    {
        global $userconn, $adminconn;
        include 'dbconn.php';
        $this->userconn = $userconn;
        $this->adminconn = $adminconn;
    }

    function getUser(string $email)
    {
        $stm = $this->userconn->prepare("select * from user where email = ?");
        $stm->execute([$email]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        return new users($user['email'], $user['password'], $user['role']);
    }
    function createUser(users $user){
        $sql = "insert into user (`email`,`password`,`role`) values (:email, :password, :role)";
        $stmt = $this->userconn->prepare($sql);
        $stmt->bindValue('email', $user->getEmail());
        $stmt->bindValue('password', $user->getPassword());
        $stmt->bindValue('role', $user->getRole());
        return $stmt->execute();
    }
}
