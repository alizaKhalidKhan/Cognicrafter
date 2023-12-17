<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register_form</title>

    <!--css stylesheet-->
    <link rel="stylesheet" href="style.css">

    <!--Toggle fields-->
    <script>
        function toggleFields() {
            var userTypeSelect = document.getElementById("user_type");
            var patientFields = document.getElementById("patient_fields");
            var careFields = document.getElementById("care_fields");

            patientFields.style.display = "none"; // Hide patient fields by default
            careFields.style.display = "none"; // Hide caregiver fields by default

            if (userTypeSelect.value === "patient") {
                patientFields.style.display = "block"; // Show patient fields
            } else if (userTypeSelect.value === "caregiver") {
                careFields.style.display = "block"; // Show caregiver fields
            }
        }
    </script>
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>REGISTER NOW</h3>
            <?php
            if(isset($error)){
                foreach($error as $error){

                };

            };
            ?>
            <input type="text" name="name" required placeholder="Enter your name">
            <input type="email" name="email" required placeholder="Enter your email">
            <input type="password" name="password" required placeholder="Enter your password">
            <input type="password" name="cpassword" required placeholder="Confirm your password">
            <select required name="user_type" id="user_type" onchange="toggleFields()">
                <option value="" selected disabled>Select User Type</option>
                <option value="patient">Patient</option>
                <option value="caregiver">Caregiver</option>
            </select>
            <div id="patient_fields" style="display: none;">
                <select required name="patient_stage" id="Patient_Stage">
                    <option value="" selected disabled>Select Patient Stage</option>
                    <option value="early">Early Stage - mild</option>
                    <option value="middle">Middle Stage - moderate</option>
                    <option value="late">Late Stage - severe</option>
                <input type="date" name="dob" placeholder="Date of Birth">
            </div>
            <div id="care_fields" style="display: none;">
                <input type="text" name="patient_email" id="patient_email" placeholder="Enter Patient Email">
            </div>


            <input type="submit" name="submit" value="register now" class="form-btn">
            <p>Already have an account? <a href="login_form.php">Login Now</a></p>
        </form>

    </div>
</body>
</html>

<?php
// Include the database connection
@include 'confg.php';

// Initialize the error array
$errors = array();

if (isset($_POST['submit'])) {
    $connect = mysqli_connect('localhost', 'root', '', 'cognicrafter');

    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = $_POST['password']; // Consider using a more secure password hashing method
    $cpassword = $_POST['cpassword'];
    $user_type = $_POST['user_type'];
    
    // Check if the email already exists
    $check_email = "SELECT * FROM patient WHERE Patient_Email = '$email'";
    $email_result = mysqli_query($connect, $check_email);

    if ($email_result === false) {
        $errors[] = 'Error: ' . mysqli_error($connect);
    } elseif (mysqli_num_rows($email_result) > 0) {
        $errors[] = 'Email is already registered. Please use a different email.';
    } else {
        if ($user_type === "caregiver") {
            $patient_email = mysqli_real_escape_string($connect, $_POST['patient_email']);
        
            // Check if the patient email exists in the patient table
            $check_patient = "SELECT patient_id FROM patient WHERE Patient_Email = '$patient_email'";
            $patient_result = mysqli_query($connect, $check_patient);
        
            if ($patient_result === false) {
                $errors[] = 'Error: ' . mysqli_error($connect);
            } elseif (mysqli_num_rows($patient_result) > 0) {
                $patient_row = mysqli_fetch_assoc($patient_result);
                $patient_id = $patient_row['patient_id'];
        
                // Insert caregiver information, including the patient_id as a foreign key
                $insert = "INSERT INTO caregiver (Patient_id, Caregiver_name, Caregiver_email, Caregiver_password) 
                           VALUES ('$patient_id','$name', '$email', '$password')";
            } else {
                $errors[] = 'Patient email not found. Please enter a valid patient email.';
            }
        } else {
            // For patients, the code remains the same
            $patient_stage = mysqli_real_escape_string($connect, $_POST['patient_stage']);
            $dob = mysqli_real_escape_string($connect, $_POST['dob']);
            // Consider using a more secure password hashing method
            //$password = password_hash($password, PASSWORD_DEFAULT);

            $insert = "INSERT INTO patient (Patient_Name, Patient_Email, Patient_Password, Patient_DOB, Patient_Stage) 
                       VALUES ('$name', '$email', '$password', '$dob', '$patient_stage')";
        }

        if (count($errors) === 0) {
            $execute = mysqli_query($connect, $insert);

            if ($execute === false) {
                $errors[] = 'Error: ' . mysqli_error($connect);
            } else {
                header('location: login_form.php');
                exit; // Add this line to prevent the script from continuing after redirect
            }
        }
    }
}

// Display JavaScript alert for each error in the errors array
echo '<script>';
foreach ($errors as $error) {
    echo 'alert("' . $error . '");';
}
echo '</script>';
?>
