<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>

    <!-- CSS stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>LOGIN NOW</h3>
            <?php
            if(isset($error)){
                foreach($error as $err){
                    echo "<script>alert('$err');</script>"; // Display each error as a JavaScript alert
                }
            }
            ?>
            <input type="text" name="email" required placeholder="enter your email">
            <input type="password" name="password" required placeholder="enter your password">
            <select name="user_type" id="user_type">
                <option value="patient">Patient</option>
                <option value="caregiver">Caregiver</option>
            </select>
            <input type="submit" name="submit" value="Login Now" class="form-btn">
            <p>Don't have an account? <a href="register_form.php">Register Now</a></p>
        </form>
    </div>
</body>
</html>

<?php
session_start();
require 'confg.php';

if (isset($_POST['submit'])) {
    $connect = mysqli_connect('localhost', 'root', '', 'cognicrafter');

    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    if ($user_type === "patient") {
        $select = "SELECT * FROM patient WHERE Patient_Email = '$email'";
        $execute = mysqli_query($connect, $select);

        if ($execute === false) {
            echo "<script>alert('Query failed: " . mysqli_error($connect) . "');</script>";
            die();
        }

        if (mysqli_num_rows($execute) > 0) {
            $row = mysqli_fetch_array($execute);
            $storedPassword = $row['Patient_Password'];

            if ($password === $storedPassword) {
                $_SESSION['user_name'] = $row['Patient_Name'];
                header('Location: p_index.php');
                exit;
            } else {
                echo "<script>alert('Incorrect password for a patient.');</script>";
            }
        } else {
            echo "<script>alert('No patient found with this email.');</script>";
        }
    } elseif ($user_type === "caregiver") {
        $email_column = "Caregiver_email";
        $password_column = "Caregiver_password";
        $select_table = "caregiver";

        $select = "SELECT * FROM $select_table WHERE $email_column = '$email'";
        $execute = mysqli_query($connect, $select);

        if ($execute === false) {
            echo "<script>alert('Query failed: " . mysqli_error($connect) . "');</script>";
            die();
        }

        if (mysqli_num_rows($execute) > 0) {
            $row = mysqli_fetch_array($execute);
            $storedPassword = $row[$password_column];

            if ($password === $storedPassword) {
                if ($user_type === 'patient') {
                    $_SESSION['user_name'] = $row['Patient_Name'];
                    header('Location: p_index.php');
                } else {
                    $_SESSION['user_name'] = $row['Caregiver_name'];
                    header('Location: c_index.php');
                }
                exit;
            } else {
                echo "<script>alert('Incorrect password for a caregiver.');</script>";
            }
        } else {
            echo "<script>alert('No caregiver found with this email.');</script>";
        }
    } else {
        echo "<script>alert('Invalid user type.');</script>";
    }

    // Close the connection to the database
    mysqli_close($connect);
}
?>
