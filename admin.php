<?php
 @include 'confg.php';
 session_start();

 if(!isset( $_SESSION['admin name'])){
    header('location:login_form.php');

 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>

    <!--css stylesheet-->
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="container">
       <div class="content">
        <h3>hi, <span>admin</span></h3>
        <h1>Welcome<span><?php echo $_SESSION['admin name']?> </span></h1>
        <p>this is an admin page</p>
        <a href="login_form.php" class="btn">login</a>
        <a href="register_form.php" class="btn">register</a>
        <a href="logout.php" class="btn">logout</a>
    </div>
    </div>
</body>
</html>