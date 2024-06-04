<?php
require_once 'config.php';
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
session_start();
if (isset($_SESSION["ffapassed"])){

    if(!isset($_POST["UOTP"])){
    //check the mfa type e= email/g= google authenticator
        if(isset($_SESSION['mfa'])&&$_SESSION['mfa']=='e'){
            $_SESSION['OTP']= strval(random_int(100000,999999));
            //send otp
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->SMTPAutoTLS = true;
            $mail->Username = 'myauthenticationproject@hotmail.com';
            $mail->Password = 'auth1234';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom($mail->Username, '2-Steps-Verification');
            $mail->addAddress($_SESSION['email'] , $_SESSION['username']);
            $mail->Subject = 'OTP';
            $mail->Body = $_SESSION['OTP'];
            $flag = $mail->send();
        }
    }else{
        if($_SESSION['mfa']=='e'){
            if($_SESSION['OTP']== $_POST["UOTP"]){
                $_SESSION["verified"] = true;
                header('location: welcome.php');}
            }elseif($_SESSION['mfa']== 'g'){
                $google2fa = new PragmaRX\Google2FA\Google2FA();
                $valid = $google2fa->verifyKey($_SESSION['usersecret'], $_POST["UOTP"]);
                if($valid) {
                $_SESSION["verified"] = true;
                header('location: welcome.php');
                }
            }else header('location: mfa.php');
            
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA</title>
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
        input[type=text]{
            padding: 6px 12px;
            position: relative;
            margin-left: 3%;
            left: 0px;
            width: 60%;
            font-size: 20px;
            font-family: fantasy;
            font-weight: 400;
            line-height: 1.5;
            color: #000000;
            background-color: #fff;
            background-clip: padding-box;
            border: 4px solid #ff0000;
            appearance: none;
            border-radius: 4px;
            transition: border-color .5s ease-in-out,box-shadow .5s ease-in-out;
            opacity: 60%;
            filter: brightness(100%);
        }
    </style>
</head>
<body>
    <center>
        <div style="margin-top:7%; width:40%; background-color: #333333;padding:2%; opacity: 85%; border-radius: 5%; box-shadow: rgba(255, 0, 0, 0.65) 5px 5px, rgba(255, 52, 52, 0.6) 10px 10px, rgba(255, 93, 93, 0.4) 15px 15px, rgba(255, 98, 98, 0.3) 20px 20px, rgba(255, 158, 158, 0.2) 25px 25px;"> 
                <form action="" method="POST">
                    <center><h2>Please enter the one time code you received</h2></center>
                    <center><label for="UOTP"><img style="display: inline-block; vertical-align:-80%; transform: rotate(270deg);" src="password.png" width="30px" ></label>
                    <input name="UOTP" id="UOTP" placeholder="OTP" type="text" maxlength="40" autocomplete="off" required autofocus></center><br>
                    <input id="signin" type="submit" class="center" style="display: flex;" value="submit"><br>
                </form>
                <br>
                <div class="center-content"><p><a style='color:#cabd00;' href="index.php">back to login</a></p></div>
        </div>
    </center>
</body>
</html>