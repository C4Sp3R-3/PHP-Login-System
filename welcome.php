<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSecurityProject</title>
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        h1{
            font-family: fantasy;
            font-size: 14ex;
            color: white;
        }
        h2{
            font-family: fantasy;
            font-size: 9ex;
            color: white;
        }
        body{
            background-color: gray;
        }
        button{
    
            display: inline-block;
            vertical-align: 100%;
            outline: 0;
            cursor: pointer;
            border-radius: 6px;
            font-family: fantasy;
            border: 2px solid #ffffff;
            color: #fff;
            background-color: #ff0800;
            margin-top: 10%;
            box-shadow:  rgba(255, 0, 0, 0.4) 6px 6px 2px ;
            font-size: 3ex;
            height: 45px;
        }
        button:hover{
            background-color: #ffffff;
            color: red;
            border-color: red;
            box-shadow:  rgba(255, 255, 255, 0.4) 6px 6px 2px ;
            
        }
        button:active{
            background-color: #ffffff;
            color: red;
            border-color: red;
            box-shadow:  rgba(255, 255, 255, 0.4) 2px 2px 2px ;
            transform: translateY(5px);
}
    </style>
</head>
<body>
<?php 
require_once 'config.php';
require_once 'vendor/autoload.php';
session_start();
if(isset($_SESSION["verified"])){

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['2faEmail'])){
            //mysql update stmt
            $query = "UPDATE users SET mfa = ? , user_secret = NULL WHERE username = ?";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt ,'ss' ,$_POST['2faEmail'], $_SESSION['username']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['mfa']='e';
        }elseif(isset($_POST['2fagoogle'])){
            $usersecret = new PragmaRX\Google2FA\Google2FA();
            $usersecret = $usersecret->generateSecretKey();
            //mysql update stmt
            $query = "UPDATE users SET mfa = ? , user_secret = ? WHERE username = ?";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt ,'sss' ,$_POST['2fagoogle'], $usersecret , $_SESSION['username']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['usersecret']= $usersecret;
            $_SESSION['mfa'] = 'g';
        }elseif(isset($_POST['disable2fa'])){
            //mysql update stmt
            $query = "UPDATE users SET mfa = ? , user_secret = NULL WHERE username = ?";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt ,'ss' ,$_POST['disable2fa'], $_SESSION['username']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['mfa']='0';
        }
    }

    if($_SESSION["privilege"] == "admin"){
        echo "<center><h1>Hello, ".$_SESSION["username"]."<br> You have <span style='color:#600000; text-decoration:underline;'>admin</span> privilege.</h1></center>";
       
    }else {
        echo "<center><h1>Hello, ".$_SESSION["username"]."<br> You have <span style='color:#600000; text-decoration:underline;'>normal</span> privilege.</h1></center>";

    }
    if($_SESSION['mfa']=='g' && isset($_SESSION["usersecret"]) ){
        echo "<center><h2>Google Authenticator secret code:  <span style='color:#600000; text-decoration:underline;'>".$_SESSION["usersecret"]."</span></h2></center>";
    }
}else header("location: index.php");

?> 
<center><div class="center-content"><p><a style='color:#cabd00;' href="blog.php">Switch to Blog view</a></p></div></center>
<center><a href="logout.php"><button>Logout</button></a></center>
<center><a href="chgemail.php"><button>Change your E-mail</button></a></center>
<center><form method="POST" action="" >
    <input type="hidden" id="2fagoogle" name="2fagoogle" value="g">
    <button type="submit">2fa via google authenticator</button>
</form></center>

<center><form method="POST" action="" >
    <input type="hidden" id="2faEmail" name="2faEmail" value="e">
    <button type="submit">2fa via email</button>
</form></center>

<center><form method="POST" action="" >
    <input type="hidden" id="disable2fa" name="disable2fa" value="0">
    <button type="submit">Disable 2fa</button>
</form></center>
</body>
</html>
