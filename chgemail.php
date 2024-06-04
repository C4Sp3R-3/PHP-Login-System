<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSecurityProject</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        h1{
            font-family: fantasy;
            font-size: 14ex;
            color: white;
        }
        h2{
            font-family: fantasy;
            font-size: 5ex;
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
        <?php 
            require_once 'config.php';
            require_once 'vendor/autoload.php';
            session_start();
            if(isset($_SESSION["verified"])){
                if(!isset($_SESSION["csrftoken"])){
                // set csrf token when the user first enters change email page
                $_SESSION["csrftoken"] = bin2hex(openssl_random_pseudo_bytes(16));
                }
                else{
                    // validating csrf token
                    if(isset($_POST["csrftoken"])&&$_POST['csrftoken']==$_SESSION['csrftoken']){
                        $email = $_POST['email'];
                        $username = $_SESSION['username'];
                        //sql update stmt
                        $query = "UPDATE users SET email = ? WHERE username = ?";
                        $stmt = mysqli_prepare($link, $query);
                        mysqli_stmt_bind_param($stmt ,'ss' , $email, $username);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);

                        //change the csrf token in case of someone knew it
                        $_SESSION["csrftoken"] = bin2hex(openssl_random_pseudo_bytes(16));

                        header("location: welcome.php");
                    }
                }

                
            }else header("location: index.php");?> 
</head>
<body>

<center><div style="margin-top:10%; width:40%; background-color: #333333;padding:2%; opacity: 85%; border-radius: 5%; box-shadow: rgba(255, 0, 0, 0.65) 5px 5px, rgba(255, 52, 52, 0.6) 10px 10px, rgba(255, 93, 93, 0.4) 15px 15px, rgba(255, 98, 98, 0.3) 20px 20px, rgba(255, 158, 158, 0.2) 25px 25px;"> 
                <form action="" method="POST">
                <center><h2>Change your E-mail</h2></center><br><br><br>
                <center>
                <input name="csrftoken" type="hidden" id = "csrftoken" value="<?php echo $_SESSION['csrftoken']; ?>">
                <input name="email" id="email" placeholder="E-mail" type="email" autocomplete="off" required autofocus></center><br>
                <input id="signin" type="submit" class="center" style="display: flex;" value="submit"><br>
            </form>
            <br>
            <div class="center-content"><p><a style='color:#cabd00;' href="welcome.php">back to mainpage</a></p></div>
        </div></center>
</body>
</html>
