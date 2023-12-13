<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Sticky Notes</title>
        <link rel="stylesheet" href="css/sticky_style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Iconsout Link For Icons-->
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
    
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

    </head>
    <body>
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
                        <a href="p_index.php" class="nav-item nav-link active">Home</a>
                        <a href="p_index.php" class="nav-item nav-link">About Us</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Features</a>
                            <div class="dropdown-menu rounded-0 rounded-bottom border-0 shadow-sm m-0">
                                <a href="p_pill.php" class="dropdown-item">Pill Reminder</a>
                                <a href="p_routine.php" class="dropdown-item">Routine Activities</a>
                                <a href="p_sticky.php" class="dropdown-item">Sticky Notes</a>
                                <a href="p_relative.php" class="dropdown-item">Relative Information</a>
                            </div>
                        </div>
                        <a href="#contact-us" class="nav-item nav-link">Contact Us</a>
                    </div>
                    <a href="" class="nav-item nav-link"><?php echo $_SESSION['user_name']; ?></a>
                    <a href="logout.php" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Logout<i class="fa fa-arrow-right ms-3"></i></a>
                </div>
            </nav>
            <!-- Navbar End -->
            <br><br>


            <!-- Sticky Notes -->
        <div class="container-xxl py-5">
            <div class="container">
                <h1 style="text-align: center;">Sticky Notes</h1><br><br><br><br>
                <div class="row g-4">
                    <?php
                    // Assuming you have a database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "cognicrafter";

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Check if the patient is logged in
                    if (isset($_SESSION['user_name'])) {
                        $patient_name = $_SESSION['user_name'];

                        // Fetch patient_id based on the session patient_name
                        $patient_query = "SELECT Patient_id FROM patient WHERE Patient_Name = '$patient_name'";
                        $patient_result = $conn->query($patient_query);

                        if ($patient_result === false) {
                            die("Error in patient query: " . $conn->error);
                        }

                        if ($patient_result->num_rows > 0) {
                            $patient_row = $patient_result->fetch_assoc();
                            $patient_id = $patient_row['Patient_id'];

                            // Fetch sticky notes data
                            $sticky_query = "SELECT s.Sticky_title, s.Sticky_description, s.Sticky_date, s.StickyNotes_id
                                            FROM stickynotes s
                                            INNER JOIN access a ON s.StickyNotes_id = a.StickyNotes_id
                                            WHERE a.Patient_id = ?";

                            $select_statement = $conn->prepare($sticky_query);
                            $select_statement->bind_param("i", $patient_id);
                            $select_statement->execute();
                            $result = $select_statement->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Generate HTML for each sticky note
                                    echo '
                                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                            <div class="sticky-notes-item">
                                            <div class="bg-light rounded-circle w-75 mx-auto p-3">
                                                    <img class="img-fluid rounded-circle" src="img/sticky.jpg" alt="">
                                                </div>
                                                <div class="bg-light rounded p-4 pt-5 mt-n5">
                                                    <h3 class="text-center mt-3 mb-4">' . $row['Sticky_title'] . '</h3>
                                                    <p>' . $row['Sticky_description'] . '</p>
                                                    <p class="text-muted">Date: ' . $row['Sticky_date'] . '</p>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }
                            } else {
                                echo "No sticky notes found for this patient.";
                            }
                        } else {
                            echo "Patient not found.";
                        }
                    } else {
                        echo "Patient not logged in.";
                    }

                    $conn->close();
                    ?>
                </div>
            </div>
        </div>


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
                            $select = "SELECT Patient_Email FROM patient WHERE Patient_Name = '{$_SESSION['user_name']}'";
                            $result = mysqli_query($connect, $select);

                            if ($result && mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $userEmail = $row['Patient_Email'];
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
    <script src="js/sticky_script.js"></script>
    
    </body>
</html>

