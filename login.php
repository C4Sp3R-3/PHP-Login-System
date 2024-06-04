<?php
session_start();
require_once "config.php";

if (isset($_SESSION['verified']))
    header("location: welcome.php");

    else{

            $flag = false;
            $response  = $_POST['g-recaptcha-response'] ; 
            $mysecret = "6LfZE6cpAAAAAMingD3NNTT11Zu46mtGLCTTsve6" ;
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = ['secret'   => $mysecret,
                    'response' => $response];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                            ]
                        ];
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $jsonArray = json_decode($result,true);
            $key = "success";
            $flag = $jsonArray[$key];


            //credentials validation
            if(isset($_POST["username"]) && isset($_POST["password"]) && $flag){

                // Validate username
                if(!empty(trim($_POST["username"]))){         
                    $username = trim($_POST["username"]);
                }else{header("location: index.php");}
                
                // Validate password
                if(!empty(trim($_POST["password"]))){
                    $password = trim($_POST["password"]);
                }else{header("location: index.php");}

                //database query initialization
                $query = "SELECT * FROM users WHERE username= ?";
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt ,'s' , $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($link);
                
                //validate database response
                if(mysqli_num_rows($result) > 0){

                    if ($result -> num_rows == 1){

                        $row = mysqli_fetch_array($result);

                        if($row["username"] == $username && password_verify($password , $row["pwd"])){
                            $_SESSION["mfa"] = $row["mfa"];
                            $_SESSION["username"] = $row['username'];
                            $_SESSION["password"] = $row['pwd'];
                            $_SESSION['email'] = $row['email'];
                            $_SESSION['avatar'] = $row['avatar'];
                            $_SESSION['usersecret'] = $row['user_secret'];
                            $_SESSION["privilege"] = ($row['privilege'] == 0)? "admin":"normal";
                            if($_SESSION['mfa']=='0'){
                                $_SESSION["verified"] = true;
                                header('location: welcome.php');}
                            else {$_SESSION['ffapassed']=true;
                                header('location: mfa.php');}
                        }else{header("location: index.php");}
                    }

                }else{header("location: index.php");}
                
            }else{header("location: index.php");}
        }
?>