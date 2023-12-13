<?php
session_start();
@include 'confg.php';

// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: c_index.php");
    exit();
}

// Check if the relative ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: c_routine.php");
    exit();
}

$routineId = $_GET['id'];

// Fetch relative information based on the relative ID
$select = "SELECT * FROM routineactivity WHERE Routine_id = '$routineId'";
$result = mysqli_query($connect, $select);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Routine not found.";
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete the relative from the database
    $delete = "DELETE FROM routineactivity WHERE Routine_id = '$routineId'";

    if (mysqli_query($connect, $delete)) {
        header("Location: c_routine.php");
        exit();
    } else {
        echo "Error deleting Routine: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CogniCrafter- Routine Activity</title>
    
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
    <!-- Include your existing HTML content here -->

    <div class="container">
        <h2 class="text-center mb-4">Confirm Deletion</h2>
        <p>Are you sure you want to delete the routine <strong><?= $row['Routine_name'] ?></strong>?</p>
        <form action="" method="post">
            <button type="submit" class="btn btn-danger" name="delete">Delete</button>
            <a href="c_routine.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connect);
?>
