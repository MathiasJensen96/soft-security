<?php include __DIR__ . "/../db/dbconn.php"?>
<?php 

    $password = $_POST['password'];
// $password = password_hash($password,PASSWORD_DEFAULT);
    $stm = $userconn->prepare("select * from user where email = ?");
    $stm->execute([$_POST['email']]);
    $user = $stm->fetch(PDO::FETCH_ASSOC);
    print_r($user);
    if ($user){
      if (password_verify($password, $user['password'])){
        echo "hurraaa";
    }  
    }
    
    
?>
<!DOCTYPE html>   
<html>   
<head>  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<title> Login Page </title>  
<style>   
Body {  
  font-family: Calibri, Helvetica, sans-serif;  
  background-color: white;  
}  
button {   
       background-color: #4CAF50;   
       width: 100%;  
        color: orange;   
        padding: 15px;   
        margin: 10px 0px;   
        border: none;   
        cursor: pointer;   
         }   
 form {   
        border: 3px solid #f1f1f1;   
    }   
 input[type=text], input[type=password] {   
        width: 100%;   
        margin: 8px 0;  
        padding: 12px 20px;   
        display: inline-block;   
        border: 2px solid green;   
        box-sizing: border-box;   
    }  
 button:hover {   
        opacity: 0.7;   
    }   
  .cancelbtn {   
        width: auto;   
        padding: 10px 18px;  
        margin: 10px 5px;  
    }   
        
     
 .container {   
        padding: 25px;   
        background-color: lightblue;  
    }   
</style>   
</head>    
<body>    
    <!-- <?php echo $password; ?> -->
    <center> <h1> Login here </h1> </center>   
    <form method="POST" action="/login">  
        <div class="container">   
            <label>Username : </label>   
            <input type="text" placeholder="Enter Email" name="email" required>  
            <label>Password : </label>   
            <input type="password" placeholder="Enter Password" name="password" required>  
            <button type="submit">Login</button>       
        </div>   
    </form>     
</body>     
</html>  