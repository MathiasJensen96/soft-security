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

    function createUser(users $user)
    {
        $sql = "insert into user (`email`,`password`,`role`) values (:email, :password, :role)";
        $stmt = $this->userconn->prepare($sql);
        $stmt->bindValue('email', $user->getEmail());
        $stmt->bindValue('password', $user->getPassword());
        $stmt->bindValue('role', $user->getRole());
        return $stmt->execute();
    }

    function updateUser(string $newEmail, string $newRole, string $oldEmail)
    {
        $stm = $this->adminconn->prepare("update `user` set `email` = ?, `role` = ? where `email` = ?");
        return $stm->execute([$newEmail, $newRole, $oldEmail]);
        //TODO: DER SKAL MÅSKE LAVES NOGET CASCADING SÅ HVIS EN USER HAR 
        // NOGLE ORDRE SÅ SKAL EMAIL OGSÅ ÆNDRES DERINDE?
    }

    function deleteUser(string $email){
        $stm = $this->adminconn->prepare("delete from user where email = ?");
        //$stm->bindValue('email', $email);
        $stm->execute([$email]);
        //TODO: IGEN, DER SKAL MÅSKE LAVES NOGET CASCADING FOR AT FJERNE ALT OM USER
    }
}
