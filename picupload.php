<?php 
require_once 'config.php';
require_once 'vendor/autoload.php';
session_start();
if(isset($_SESSION["verified"])){
    if(isset($_FILES["profilepic"])){
        //saving location
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profilepic"]["name"]);
        //file type check and image validity
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["profilepic"]["tmp_name"]);
        if($check !== false) {
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg") {
                // prepare the saving name using md5 and salt
                $newfname = $_SESSION["username"].md5_file($_FILES['profilepic']['tmp_name']).'.'.$imageFileType;
                $target_file = $target_dir.$newfname;
                //saving
                move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file);

                //sql update stmt to save the pic address
                $username = $_SESSION["username"];
                $query = "UPDATE users SET avatar = ? WHERE username = ?";
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt ,'ss' , $newfname, $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                //update session
                $_SESSION['avatar'] = $newfname;
                header("location: blog.php"); exit;
            }else echo"file type not supported.";

          }else {header("location: blog.php"); exit;}
       
    }else header("location: blog.php");

}else header("location: index.php");?> 