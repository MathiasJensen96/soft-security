<?php
include __DIR__ . "/../db/UserDao.php";
//include __DIR__. "/../entities/users.php";
$userdao = new UserDao();

// if(isset($_POST["submit"])){
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = $_POST["email"];
    $pword = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $user = new users($email, $pword, "user");
    //print_r($user); //debug line
    if ($userdao->getUser($user->getEmail())){
        echo "A user with that email already exists";
    }else{
        if($userdao->createUser($user)){
            return http_response_code(204);
        }else{
            //TODO: ERROR HANDLING
            echo "something went wrong";
            return http_response_code(400);
        }
    }
    
    //TODO: if it doesn't exist create this new user in database and 
    // return the user/or http response.
    


    // }else{
    //     header("Location: ../");
    // }
}