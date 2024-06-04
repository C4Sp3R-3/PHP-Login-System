<?php

require_once "config.php";
 
if(isset($_POST["Submit"])&&isset($_POST["username"])&&isset($_POST["password"])&&isset($_POST["email"])){
 
    // Validate username
    if(!empty(trim($_POST["username"]))){         
        $username = trim($_POST["username"]);
    }else{header("location: signup.php");}
    
    // Validate password
    if(!empty(trim($_POST["password"]))){
        $password = trim($_POST["password"]);
    }else{header("location: signup.php");}

    // Validate email
    if(!empty(trim($_POST["email"]))){
        $email = trim($_POST["email"]);
    }
        
   
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, pwd, email, privilege) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            $privilege = 1 ;
            $password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssss", $username, $password, $email, $privilege);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Close statement
                mysqli_stmt_close($stmt);

                 // Close connection
                mysqli_close($link);
                
                // Redirect to login page
                header("location: index.php");
            }   
        }else{header("location: signup.php");}
    }
    
   

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
        label{
            color: white;
        }
        h1,h2{
            color:red;
            font-family: fantasy;
        }
    </style>
</head>
<body class="center-content" style="background-color: gray; padding:7%;">
<div class="maindiv" style=" padding:2%;  background-color: #222222; opacity: 85%; height: 65%; width: 30%; border-radius: 5%; box-shadow: rgba(255, 0, 0, 0.65) 5px 5px, rgba(255, 52, 52, 0.6) 10px 10px, rgba(255, 93, 93, 0.4) 15px 15px, rgba(255, 98, 98, 0.3) 20px 20px, rgba(255, 158, 158, 0.2) 25px 25px;"> 
        <h2 class="center-content" style="margin-top: 2%;" >Sign Up</h2>
        <p class="center-content" style="margin-top: 2%; font-size:2.5ex;" >Please fill this form to create an account.</p>
        <form action="" method="post">
            <div class="form-group " >
                <input type="text" placeholder="Username" name="username" autocomplete="off" class="form-control">
            </div>
            <div class="form-group"  >
                <input type="email" placeholder="E-mail" name="email" autocomplete="off" class="form-control">
            </div>   
            <div class="form-group" >
                <input placeholder="Password" type="password" name="password" class="form-control">
            </div>
            <div class="form-group center-content">
                <input type="submit" class="btn btn-primary" value="Submit" name='Submit'>
            </div>
            <div style="margin-top:7%;"><p>Already have an account? <a href="index.php">Login here</a>.</p></div>
        </form>
    </div>    
</body>
</html>