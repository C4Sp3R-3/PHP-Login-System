<?php 
require_once 'config.php';
session_start();
if (isset($_GET['code'])){

    //getting token info from google api
    $client = new Google_Client();
    $client -> setClientId(clientID);
    $client -> setClientSecret(clientSecret);
    $client -> setRedirectUri(redirectURL);
    $client -> addScope('profile');
    $client -> addScope('email');
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client ->setAccessToken($token);

    //get user info
    $gauth = new Google_Service_Oauth2($client);
    $userInfo = $gauth->userinfo->get();
    $email = $userInfo->email;
    $username = $userInfo->name;
    
    //checking if the user already exists
    //database query initialization
    $query = "SELECT * FROM users WHERE username= ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt ,'s' , $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    //validate database response
    if(mysqli_num_rows($result) > 0){

        if ($result -> num_rows == 1){
            $row = mysqli_fetch_array($result);
            $_SESSION["username"] = $row['username'];
            $_SESSION["privilege"] = ($row['privilege'] == 0)? "admin":"normal";
            $_SESSION['mfa'] = $row['mfa'];
            $_SESSION['avatar'] = $row['avatar'];
            $_SESSION['usersecret'] = $row['user_secret'];
            $_SESSION["verified"] = true;
            header('location: welcome.php');
        }
    }else{
            //if new user
            $sql = "INSERT INTO users (username, pwd, email, privilege) VALUES (?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                $privilege = 1 ;
                $password = bin2hex(openssl_random_pseudo_bytes(16));
                $password = password_hash($password, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, "ssss", $username, $password , $email, $privilege);

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Close statement
                    mysqli_stmt_close($stmt);

                    // Close connection
                    mysqli_close($link);
                    
                    //verifying the session
                    $_SESSION["username"] = $username;
                    $_SESSION["privilege"] = "normal";
                    $_SESSION['mfa'] = '0';
                    $_SESSION["verified"] = true;
                    header('location: welcome.php');
                }

            }    
        }

}
?>