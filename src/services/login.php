<?php include __DIR__ . "/../db/dbconn.php"?>
<?php
session_start();
    $password = $_POST['password'];
    $stm = $userconn->prepare("select * from user where email = ?");
    $stm->execute([$_POST['email']]);
    $user = $stm->fetch(PDO::FETCH_ASSOC);
    //lav en user class til at være = $user
    print_r($user); //debug
    if ($user){
      if (password_verify($password, $user['password'])){
        echo "<h1>hurraaa</h1>";
        // set session variables here i guess
        }  
    }