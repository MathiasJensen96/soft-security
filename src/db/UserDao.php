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

    function getUserById(int $id): ?users
    {
        $stmt = $this->userconn->prepare("select * from user where id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        return new users($user['email'], $user['password'], $user['role'], $user['id']);
    }

    function getUserByEmail(string $email): ?users
    {
        $stmt = $this->userconn->prepare("select * from user where email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        return new users($user['email'], $user['password'], $user['role'], $user['id']);
    }

    function createUser(users $user)
    {
        $sql = "insert into user (`email`,`password`,`role`) values (:email, :password, :role)";
        $stmt = $this->userconn->prepare($sql);
        $stmt->bindValue('email', $user->getEmail());
        $stmt->bindValue('password', $user->getPassword());
        $stmt->bindValue('role', $user->getRole());
        return $stmt->execute();
    }

    function updateUser(int $id, string $newEmail, string $newRole)
    {
        $stmt = $this->adminconn->prepare("update `user` set `email` = ?, `role` = ? where `id` = ?");
        return $stmt->execute([$newEmail, $newRole, $id]);
        //TODO: DER SKAL MÅSKE LAVES NOGET CASCADING SÅ HVIS EN USER HAR 
        // NOGLE ORDRE SÅ SKAL EMAIL OGSÅ ÆNDRES DERINDE?
    }

    function deleteUser(int $id){
        $stmt = $this->adminconn->prepare("delete from user where id = ?");
        $stmt->execute([$id]);
        //TODO: IGEN, DER SKAL MÅSKE LAVES NOGET CASCADING FOR AT FJERNE ALT OM USER
    }
}
