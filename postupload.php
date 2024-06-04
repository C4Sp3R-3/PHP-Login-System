<?php
require_once 'config.php';
require_once 'vendor/autoload.php';
session_start();
if(isset($_SESSION["verified"])&&$_SERVER['REQUEST_METHOD'] == 'POST'){
    //post sql insert stmt
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_SESSION['username'];
    $sql = "INSERT INTO posts (author , title , description) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $author, $title, $description);
    mysqli_stmt_execute($stmt);
    header("location: blog.php");
}else header("location: index.php");

?>