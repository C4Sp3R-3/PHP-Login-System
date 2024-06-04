<?php
session_start();
require_once 'config.php';

//google client request
$client = new Google_Client();
$client -> setClientId(clientID);
$client -> setClientSecret(clientSecret);
$client -> setRedirectUri(redirectURL);
$client -> addScope('profile');
$client -> addScope('email');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stylesheet.css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>AuthGeeksProject</title>
    <style>
        .login-with-google-btn {
    transition: background-color .3s, box-shadow .3s;
    
    transform: scale(1.4);

    margin-top: 5%;
    margin-bottom: 5%;

    padding: 12px 16px 12px 42px;
    border: none;
    border-radius: 3px;
    box-shadow: rgba(255, 0, 0, 0.65) 4px 4px;
    
    color: #757575;
    font-size: 14px;
    font-weight: 500;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;
    
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
    background-color: white;
    background-repeat: no-repeat;
    background-position: 12px 11px;
    
    &:hover {
      box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 2px 4px rgba(0, 0, 0, .25);
    }
    
    &:active {
      background-color: #eeeeee;
    }
    
    &:focus {
      outline: none;
      box-shadow: 
        0 -1px 0 rgba(0, 0, 0, .04),
        0 2px 4px rgba(0, 0, 0, .25),
        0 0 0 3px #ff0800;
    }
    
    &:disabled {
      filter: grayscale(100%);
      background-color: #ebebeb;
      box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
      cursor: not-allowed;
    }
  }

  body {
    text-align: center;
    padding-top: 2rem;
  }

    </style>
</head>
<body class="fullscreen100vh">
    <div class="center-content fullscreen100vh" style="z-index: -1;">
    <img src="wallpaper.jpg" style=" justify-self: center ; position: absolute; height: 100hv; width: 133vh;" >
    </div>
    <div class="float-child-left">
        <p style="top: 5%; position: absolute; font-size: 18ex; color: rgb(206, 0, 0); font-weight: 500;">AuthGeeks</p>
        <p style="top: 27%; position: absolute;font-size: 14ex; font-weight: bolder; color: #b3a100;">#1</p><br>
        <p style="top: 40%; position: absolute;font-size: 10ex; color: #cabd00;">In </p>
        <p style="top: 40%; position: absolute;font-size: 10ex; color: #ff0000; left: 13%;">Authentication</p>
        <p style="top: 47%; position: absolute;font-size: 10ex; color: #cabd00;">Security</p>
    </div>
    <div class="float-child-right">
        <div class="maindiv" style="margin: auto; background-color: rgb(0, 0, 0);margin-left: 40%; opacity: 85%; height: 83%; width: 40%; border-radius: 5%; box-shadow: rgba(255, 0, 0, 0.65) 5px 5px, rgba(255, 52, 52, 0.6) 10px 10px, rgba(255, 93, 93, 0.4) 15px 15px, rgba(255, 98, 98, 0.3) 20px 20px, rgba(255, 158, 158, 0.2) 25px 25px;"> 
            <form action="login.php" method="POST">
                <label for="username" style="margin-left: 0% ;"><img style="display: inline-block; vertical-align:-50%;" src="user.png" width="30px" ></label>
                <input name="username" id="username" placeholder="Username" type="text" maxlength="20" autocomplete="off" required autofocus><br><br>
                <label for="password" style="margin-left: 0% ;"><img style="display: inline-block; vertical-align:-120%; transform: rotate(270deg); scale: 70%;" src="password.png" width="30px" ></label>
                <input name="password" id="password" placeholder="Password" type="password" maxlength="24" required><br>
                <div class="center-content"><div style="margin-top: 5%; box-shadow: rgba(255, 0, 0, 0.4) 5px 5px ,rgba(255, 0, 0, 0.2) 10px 10px,rgba(255, 0, 0, 0.1) 15px 15px;" class="g-recaptcha" data-sitekey="6LfZE6cpAAAAAFsjygLSBkZ97T_yKkRcKW6yZR1Y"></div></div>
                <input id="signin" type="submit" class="center" style="display: flex;" value="Logn In"><br>
            </form>
            <div class="center-content"><p style="font-size: 3ex;">Don't have an account?</p></div>
            <div class="center-content"><a href="<?php echo $client->createAuthUrl(); ?>" ><button type="submit" class="login-with-google-btn" >Continue with Google</button></a></div>
            <div class="center-content"><p style="font-size: 3.5ex;">or</p></div>
            <div class="center-content"><p><a href="signup.php">Create one</a></p></div>
            <div><p>Forgot your password? <a href="resetpwdform.php">Reset it</a></p></div>
        </div>

    </div>

</body>
</html>
