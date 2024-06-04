<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title>password reset</title>

    <?php 
    session_start();
    require_once "config.php";
    require __DIR__.'/vendor/autoload.php';
    

    //if set, dont show the form
    if (isset($_SESSION["pwdrstreq"])){
        echo"<center><h1 style='margin-top:10%; box-shadow: rgba(255, 0, 0, 0.4) 5px 5px ,rgba(255, 0, 0, 0.2) 10px 10px,rgba(255, 0, 0, 0.1) 15px 15px;'>An E-mail has been sent to reset your password.</h1><center><br><br>";
        echo"<center><p style='font-size:4ex;text-decoration:underline;'><a style='color:#cabd00;' href='index.php'>back to login</a></p></center>";
        session_destroy();
        exit;
    }elseif (isset($_POST['username'])){
            $username = $_POST['username'];

            //database query initialization
            $query = "SELECT * FROM users WHERE username= ?";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt ,'s' , $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            
            //validate database response
            if(mysqli_num_rows($result) > 0){

                if ($result -> num_rows == 1){
                    
                    $row = mysqli_fetch_array($result);
                    $email = $row['email'];
                    $token = bin2hex(openssl_random_pseudo_bytes(16));

                    //sql query to check if there is a token already

                    $query = "SELECT * FROM pwdtokens WHERE username= ?";
                    $stmt = mysqli_prepare($link, $query);
                    mysqli_stmt_bind_param($stmt ,'s' , $username);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    mysqli_stmt_close($stmt);

                    if(mysqli_num_rows($result) > 0){
                        //mysql update stmt
                        $query = "UPDATE pwdtokens SET token = ? WHERE username = ?";
                        $stmt = mysqli_prepare($link, $query);
                        mysqli_stmt_bind_param($stmt ,'ss' , $token, $username);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        mysqli_close($link);


                    }else{
                        //mysql insert stmt
                        $query = "INSERT INTO pwdtokens(username , token) VALUES(?,?)";
                        $stmt = mysqli_prepare($link, $query);
                        mysqli_stmt_bind_param($stmt ,'ss' , $username, $token);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        mysqli_close($link);
                    }
                    
                    //send reset url via email
                    $mail->setFrom($mail->Username, 'account recovery');
                    $mail->addAddress($email , $username);
                    $mail->Subject = 'password reset url';
                    $mail->Body = 'http://localhost/resetpwd.php?token='.$token;
                    $flag = $mail->send();

                    if($flag){ $_SESSION["pwdrstreq"] = 'sent';header('location: resetpwdform.php');exit;}
                    else echo $mail->ErrorInfo;
             
                }
            }else{
                echo"<center><h1 style='margin-top:10%; box-shadow: rgba(255, 0, 0, 0.4) 5px 5px ,rgba(255, 0, 0, 0.2) 10px 10px,rgba(255, 0, 0, 0.1) 15px 15px;'>No such a username.</h1><center><br><br>";
                echo"<center><p style='font-size:4ex;text-decoration:underline;'><a style='color:#cabd00;' href='index.php'>back to login</a></p></center>";
                session_destroy();
                exit;
            }        
    }
    ?>


</head>
<body>

    <center><div style="margin-top:10%; width:40%; background-color: #333333;padding:2%; opacity: 85%; border-radius: 5%; box-shadow: rgba(255, 0, 0, 0.65) 5px 5px, rgba(255, 52, 52, 0.6) 10px 10px, rgba(255, 93, 93, 0.4) 15px 15px, rgba(255, 98, 98, 0.3) 20px 20px, rgba(255, 158, 158, 0.2) 25px 25px;"> 
                <form action="" method="POST">
                <center><h2>Recover your account</h2></center>
                <center><label for="username"><img style="display: inline-block; vertical-align:-50%;" src="user.png" width="30px" ></label>
                <input name="username" id="username" placeholder="Username" type="text" maxlength="20" autocomplete="off" required autofocus></center><br>
                <input id="signin" type="submit" class="center" style="display: flex;" value="submit"><br>
            </form>
            <br>
            <div class="center-content"><p><a style='color:#cabd00;' href="index.php">back to login</a></p></div>
        </div></center>
    
</body>
</html>
