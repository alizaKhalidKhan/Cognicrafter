<?php
session_start();
@include 'confg.php';

// Check if the patient is logged in
if (isset($_SESSION['user_name'])) {
    $care_name = $_SESSION['user_name'];

    // Fetch patient_id based on the session 
    $patient_query = "SELECT Patient_id FROM caregiver WHERE Caregiver_name = '$care_name'";
    $patient_result = $connect->query($patient_query);

    if ($patient_result === false) {
        die("Error in patient query: " . $connect->error);
    }

    if ($patient_result->num_rows > 0) {
        $patient_row = $patient_result->fetch_assoc();
        $patientId = $patient_row['Patient_id'];

        // Fetch saved routine information from the database
        $select = "SELECT * FROM routineactivity WHERE Patient_id = '$patientId'";
        $result = mysqli_query($connect, $select);

        if ($result === false) {
            die("Error in pill query: " . mysqli_error($connect));
        }
    } else {
        echo "Patient not found.";
    }
} else {
    echo "Patient not logged in.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CogniCrafter- Routine Activities</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!--Display pop up once the information is saved script code-->
    <script>
    // Check if the session variable is set and display the popup
    <?php
    if (isset($_SESSION['activity_saved']) && $_SESSION['activity_saved']) {
        echo 'alert("Routine Activity saved successfully.");';
        // Reset the session variable
        unset($_SESSION['activity_saved']);
    }
    ?>
    </script>
</head>

<body>
    <section id="home">
    <div class="container-xxl bg-white p-0">
        
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
            <a href="index.html" class="navbar-brand">
               <img src="img/C.png" alt="" class="navbar-Logo">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="c_index.php" class="nav-item nav-link active">Home</a>
                    <a href="#about-us" class="nav-item nav-link">About Us</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Features</a>
                        <div class="dropdown-menu rounded-0 rounded-bottom border-0 shadow-sm m-0">
                            <a href="c_pill.php" class="dropdown-item">Pill Reminder</a>
                            <a href="c_routine.php" class="dropdown-item">Routine Activities</a>
                            <a href="c_sticky.php" class="dropdown-item">Sticky Notes</a>
                            <a href="c_relative.php" class="dropdown-item">Relative Information</a>
                        </div>
                    </div>
                    <a href="#contact-us" class="nav-item nav-link">Contact Us</a>
                </div>
                <a href="" class="nav-item nav-link"><?php echo $_SESSION['user_name']; ?></a>
                <a href="logout.php" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Logout<i class="fa fa-arrow-right ms-3"></i></a>
            </div>
        </nav>
        <!-- Navbar End -->


        <!--Routine form starts-->
        <br><br>

<div class="form-container" style="background-color: white;">
    <form action="" method="post">
    <h2 class="mb-3">Routine Activities</h2>
        <?php
        if(isset($error)){
            foreach($error as $error){

            };

        };
        ?>
        <input type="text" name="name" required placeholder="Enter Routine Name">
        <select required name="routine_type" id="routine_type">
                <option value="" selected disabled>Select Routine Type</option>
                <option value="healthcare">Healthcare</option>
                <option value="financial">Financial</option>
                <option value="leisure">leisure</option>
            </select>
        <input type="time" name="time" placeholder="Enter Routine time">
        <input type="date" name="date" placeholder="Enter Routine Date">
        <input type="submit" name="submit" value="Save" class="form-btn">
    </form>

</div>
         <!-- Display saved data in a table -->
    <section id="saved-data" class="py-5">
            <div class="container">
            <?php
    // Display the saved information in a table
    if ($result->num_rows > 0) {
        echo '<section id="saved-data" class="py-5">
                <div class="container">
                    <h2 class="text-center mb-4">Saved Routine Information</h2>
                    <!-- Display the table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Routine Name</th>
                                <th>Routine Time</th>
                                <th>Routine Date</th>
                                <th>Routine Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';
        while ($row= mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . $row['Routine_name'] . '</td>
                    <td>' . $row['Routine_time'] . '</td>
                    <td>' . $row['Routine_date'] . '</td>
                    <td>' . $row['Routine_type'] . '</td>
                    <td>
                        <a href="edit_routine.php?id=' . $row['Routine_id'] . '">Edit</a> |
                        <a href="delete_routine.php?id=' . $row['Routine_id'] . '" onclick="return confirm(\'Are you sure you want to delete this routine?\')">Delete</a>
                    </td>
                </tr>';
        }
        echo '</tbody>
            </table>
        </div>
    </section>';
    } else {
        echo "No routine information found for this patient.";
    }

    ?>
            </tbody>
        </table>
    </div>
</section>

        <section id="contact-us" style="background-color: black !important;">
        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s" style="background-color: black !important;">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Get In Touch</h3>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>North Karachi, Karachi</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@CogniCrafter.com</p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <!-- Add other content if needed -->
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">Social Accounts</h3>
                        <div class="row g-2 pt-2">
                            <div class="d-flex pt-2">
                                <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (isset($_SESSION['user_name'])): ?>
                    <!-- User is logged in -->
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">You are logged in as</h3>
                        <p class="user"><?php echo $_SESSION['user_name']; ?></p>
                        <?php
                        require 'confg.php';
                            // Assuming you have stored the user's email in 'Patient_Email'
                            $select = "SELECT Caregiver_email FROM caregiver WHERE Caregiver_name = '{$_SESSION['user_name']}'";
                            $result = mysqli_query($connect, $select);

                            if ($result && mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $userEmail = $row['Caregiver_email'];
                                echo "<p class='email'>$userEmail</p>";
                            }
                            ?>
                        <p><a href="logout.php">Log out</a></p>
                    </div>
                <?php else: ?>
                    <!-- User is not logged in, display login or signup options -->
                    <div class="col-lg-3 col-md-6">
                        <h3 class="text-white mb-4">SignUp Now</h3>
                        <div class="position-relative mx-auto" style="max-width: 5px;">
                            <a href="logout.php" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Logout</a>
                        </div>
                    </div>
                <?php endif; ?>

                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">CogniCrafter</a>, All Right Reserved. 
                            
                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                            Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

    </section>


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/scroll.js"></script>
</body>

</html>

<?php
// Include the configuration file
@include 'confg.php';


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_name'])) {
    // Get caregiver (patient) ID using the session information
    $caregiverName = $_SESSION['user_name'];
    $selectPatientId = "SELECT Patient_id, Caregiver_id FROM caregiver WHERE Caregiver_name = '$caregiverName'";
    $resultPatientId = mysqli_query($connect, $selectPatientId);

    if ($resultPatientId && mysqli_num_rows($resultPatientId) > 0) {
        $rowPatientId = mysqli_fetch_assoc($resultPatientId);
        $patientId = $rowPatientId['Patient_id'];
        $caregiver_id = $rowPatientId['Caregiver_id'];

        // Get relative information from the form
        $RoutineName = mysqli_real_escape_string($connect, $_POST['name']);
        $RoutineDate = mysqli_real_escape_string($connect, $_POST['date']);
        $RoutineTime = mysqli_real_escape_string($connect, $_POST['time']);  
        $RoutineType = mysqli_real_escape_string($connect, $_POST['routine_type']);  

        // Insert the relative information into the relative table
        $insertRoutine = "INSERT INTO routineactivity (Routine_name, Caregiver_id , Patient_id, Routine_date, Routine_time, Routine_type) 
                           VALUES ('$RoutineName', '$caregiver_id', '$patientId', '$RoutineDate', '$RoutineTime', '$RoutineType')";

        if (mysqli_query($connect, $insertRoutine)) {
            // Set a session variable for displaying success message
            $_SESSION['activity_saved'] = true;
        } else {
            echo "Error: " . $insertRoutine . "<br>" . mysqli_error($connect);
        }
    } else {
        echo "Error retrieving patient ID.";
    }
}

// Close the database connection
mysqli_close($connect);

?>
