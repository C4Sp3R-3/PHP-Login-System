<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSecurityProject</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
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
        h3{
            font-family: fantasy;
            font-size: 5ex;
            color: white;
        }
        h4{
            font-family: fantasy;
            font-size: 2ex;
            color: white;
        }
        p{
            font-family: fantasy;
            font-size: 3ex;
        }
        body{
            background-color: gray;
        }
        input[type=text]{
            margin-top: 3%;
            margin-left: 3%;
            width: 60%;
            padding: 6px 12px;
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
        input[type=text]:focus{
            margin-top: 3%;
            margin-left: 3%;
            width: 60%;
            padding: 6px 12px;
            font-size: 26px;
            font-family: fantasy;
            font-weight: 400;
            line-height: 1.5;
            color: #000000;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 6px none #ffffff;
            appearance: none;
            border-radius: 5px;
            box-shadow:rgba(255, 0, 0, 0.281) 5px 7px 2px ;
            transition: border-color .7s ease-in-out,box-shadow .5s ease-in-out;
            opacity: 60%;
            filter: brightness(100%);
        }
        .post{
            margin-top: 1%;
            border-color: #ffbd00;
            border-style: solid;
            border-width: 4px;
            padding: 10px;
            width: 50%;
        }
        .publish{
            display: inline-block;
            vertical-align: 100%;
            outline: 0;
            cursor: pointer;
            border-radius: 6px;
            font-family: fantasy;
            border: 2px solid #ffffff;
            color: #fff;
            background-color: #ff0800;
            margin-top: 3%;
            box-shadow:  rgba(255, 0, 0, 0.4) 6px 6px 2px ;
            font-size: 3ex;
            height: 45px;
        }
        .publish:hover{
            background-color: #ffffff;
            color: red;
            border-color: red;
            box-shadow:  rgba(255, 255, 255, 0.4) 6px 6px 2px ;
        }
        .publish:active{
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
          // sql stmt to fetch posts
          $query = "SELECT * FROM posts";
          $stmt = mysqli_prepare($link, $query);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
        }else header("location: index.php");?> 
</head>
<body>
    <div class="border" style="width:200px; height:250px"><img src="uploads/<?php echo $_SESSION["avatar"]; ?>" style="width:100%; height:100%;"></div>
    <form action="picupload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profilepic" id="profilepic">
        <input type="submit">
    </form>

    <center>
        <div style="width:40%; background-color: #333333;padding:2%; opacity: 85%; border-radius: 5%; box-shadow: rgba(255, 0, 0, 0.65) 5px 5px, rgba(255, 52, 52, 0.6) 10px 10px, rgba(255, 93, 93, 0.4) 15px 15px, rgba(255, 98, 98, 0.3) 20px 20px, rgba(255, 158, 158, 0.2) 25px 25px;"> 
        <form action="postupload.php" method="POST">
        <center><h2>Publish a post</h2></center><br>
        <center>
            <input type="text" name="title" id="title" placeholder="Title">
            <input type="text" name="description" id="description" placeholder="Description">
        
            <input class="publish" id="signin" type="submit" class="center" style="display: flex;" value="Publish!"><br>
            </form></div>
            <br><br><br>
            <h2>Discover the new posts</h2>
        </center>

    <br><br><br>
        <?php 
            //fetch posts
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<center>';
                    echo "<div class='post'>";
                    echo '<center>';
                    echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                    echo "<p>" . nl2br(htmlspecialchars($row["description"])) . "</p>";
                    echo "<h4>Author: " . htmlspecialchars($row["author"]) . "</h4>";
                    echo '</center>';
                    echo "</div>";
                    echo '</center>';
                }
            }
        ?>
    

</body>
</html>
