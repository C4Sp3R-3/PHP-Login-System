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

    //if not set, dont show the form
    if (isset($_GET["token"])){
        $token = $_GET["token"];
        

        //initialize query to get the user by token
        
        $query = "SELECT * FROM pwdtokens WHERE token= ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt ,'s' , $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        //if no result show this link is expired
        if(mysqli_num_rows($result) > 0){

            //if set, update the db and show msg // if not, show the new password form
            if (isset($_POST["password"])){
                
            $password = $_POST["password"];
            $password = password_hash($password, PASSWORD_DEFAULT);

            
                $row = mysqli_fetch_array($result);
                $username = $row['username'];

                //mysql update stmt
                $query = "UPDATE users SET pwd = ? WHERE username = ?";
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt ,'ss' , $password, $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                
                //mysql delete stmt
                $query = "DELETE FROM pwdtokens WHERE token = ?";
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt ,'s' , $token);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                //show msg
                echo"<center><h1 style='margin-top:10%; box-shadow: rgba(255, 0, 0, 0.4) 5px 5px ,rgba(255, 0, 0, 0.2) 10px 10px,rgba(255, 0, 0, 0.1) 15px 15px;'>Your password has been updated.</h1><center><br><br>";
                echo"<center><p style='font-size:4ex;text-decoration:underline;'><a style='color:#cabd00;' href='index.php'>back to login</a></p></center>";
                session_destroy();
                exit;


            }

        }else{
            echo"<center><h1 style='margin-top:10%; box-shadow: rgba(255, 0, 0, 0.4) 5px 5px ,rgba(255, 0, 0, 0.2) 10px 10px,rgba(255, 0, 0, 0.1) 15px 15px;'>This link is expired.</h1><center><br><br>";
            echo"<center><p style='font-size:4ex;text-decoration:underline;'><a style='color:#cabd00;' href='index.php'>back to login</a></p></center>";
            session_destroy();
            exit;

            }
    }
    else{
        echo"<center><h1 style='margin-top:10%; box-shadow: rgba(255, 0, 0, 0.4) 5px 5px ,rgba(255, 0, 0, 0.2) 10px 10px,rgba(255, 0, 0, 0.1) 15px 15px;'>Invalid link.</h1><center><br><br>";
        echo"<center><p style='font-size:4ex;text-decoration:underline;'><a style='color:#cabd00;' href='index.php'>back to login</a></p></center>";
        session_destroy();
        exit;
    }



    ?>

</head>
<body>

    <center><div style="margin-top:7%; width:40%; background-color: #333333;padding:2%; opacity: 85%; border-radius: 5%; box-shadow: rgba(255, 0, 0, 0.65) 5px 5px, rgba(255, 52, 52, 0.6) 10px 10px, rgba(255, 93, 93, 0.4) 15px 15px, rgba(255, 98, 98, 0.3) 20px 20px, rgba(255, 158, 158, 0.2) 25px 25px;"> 
            <form action="" method="POST">
                <center><h2>Please enter a new password</h2></center><br><br><br><br>
                <center><label for="username"><img style="display: inline-block; vertical-align:-80%; transform: rotate(270deg);" src="password.png" width="30px" ></label>
                <input name="password" id="password" placeholder="New password" type="password" maxlength="40" autocomplete="off" required autofocus></center><br>
                <input id="signin" type="submit" class="center" style="display: flex;" value="submit"><br>
            </form>
            <br>
            <div class="center-content"><p><a style='color:#cabd00;' href="index.php">back to login</a></p></div>
        </div></center>

</body>

</html>