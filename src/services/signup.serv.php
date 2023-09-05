<?php
include __DIR__ . "/../db/dbconn.php";
include __DIR__. "/../entities/users.php";
$sqlexist = $userconn->prepare("select * from user where email = ?");
$sqlcreate = $userconn->prepare("insert into user (`email`,`password`,`role`) values (?,?,?)");
// if(isset($_POST["submit"])){
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = $_POST["email"];
    $pword = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $user = new users($email, $pword, "user");
    //print_r($user); //debug line
    $sqlexist->execute([$user->getEmail()]);
    if ($sqlexist->fetch(PDO::FETCH_ASSOC)){
        echo "A user with that email already exists";
    }else{
        $sqlcreate->execute([$user->getEmail(),$user->getPassword(),$user->getRole()]);
        return http_response_code(200);
    }
    
    //TODO: if it doesn't exist create this new user in database and 
    // return the user/or http response.
    


    // }else{
    //     header("Location: ../");
    // }
}