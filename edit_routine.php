<?php
session_start();
@include 'confg.php';

// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: c_index.php");
    exit();
}

// Check if the Pill ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: c_routine.php");
    exit();
}

$routineId = $_GET['id'];

// Fetch pill information based on the Pill ID
$select = "SELECT * FROM routineactivity WHERE Routine_id = '$routineId'";
$result= mysqli_query($connect, $select);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Routine not found.";
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated relative information from the form
    $routineName = mysqli_real_escape_string($connect, $_POST['name']);
    $routineTime = mysqli_real_escape_string($connect, $_POST['time']);
    $routineDate = mysqli_real_escape_string($connect, $_POST['date']);
    $routineType = mysqli_real_escape_string($connect, $_POST['routine_type']);

    // Update the relative information in the database
    $updateRoutine = "UPDATE routineactivity SET
                    Routine_name = '$routineName',
                    Routine_date = '$routineDate',
                    Routine_time = '$routineTime',
                    Routine_type = '$routineType'
                WHERE Routine_id = '$routineId'";

    if (mysqli_query($connect, $updateRoutine)) {
        header("Location: c_routine.php");
        exit();
    } else {
        echo "Error updatingroutine information: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
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

    <div class="form-container" style="background-color: white;">
        <form action="" method="post">
            <h2 class="mb-3">Edit Routine Information</h2>
            <input type="text" name="name" required placeholder="Enter Routine Name" value="<?= $row['Routine_name'] ?>">
            <select required name="routine_type" id="routine_type" value="<?= $row['Routine_type'] ?>">
                <option value="" selected disabled>Select Routine Type</option>
                <option value="healthcare">Healthcare</option>
                <option value="financial">Financial</option>
                <option value="leisure">leisure</option>
            </select>
            <input type="time" name="time" placeholder="Enter Routine time" value="<?= $row['Routine_time'] ?>">
             <input type="date" name="date" placeholder="Enter Routine Date" value="<?= $row['Routine_date'] ?>">
            <input type="submit" name="submit" value="Update" class="form-btn">
        </form>
    </div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connect);
?>
