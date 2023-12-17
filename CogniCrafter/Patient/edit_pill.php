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
    header("Location: c_pill.php");
    exit();
}

$pillId = $_GET['id'];

// Fetch pill information based on the Pill ID
$selectPill = "SELECT * FROM pillreminder WHERE Pill_id = '$pillId'";
$resultPill = mysqli_query($connect, $selectPill);

if (!$resultPill || mysqli_num_rows($resultPill) === 0) {
    echo "Pill not found.";
    exit();
}

$rowPill = mysqli_fetch_assoc($resultPill);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated relative information from the form
    $pillName = mysqli_real_escape_string($connect, $_POST['name']);
    $pillTime = mysqli_real_escape_string($connect, $_POST['time']);
    $pillDose = mysqli_real_escape_string($connect, $_POST['dose']);

    // Update the relative information in the database
    $updatePill = "UPDATE pillreminder SET
                    Pill_name = '$pillName',
                    Pill_dose = '$pillDose',
                    Pill_time = '$pillTime'
                WHERE Pill_id = '$pillId'";

    if (mysqli_query($connect, $updatePill)) {
        header("Location: c_pill.php");
        exit();
    } else {
        echo "Error updating pill information: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CogniCrafter- Pill Reminder</title>
    
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
            <h2 class="mb-3">Edit Pill Information</h2>
            <input type="text" name="name" required placeholder="Enter Pill name" value="<?= $rowPill['Pill_name'] ?>">
            <input type="text" name="dose" required placeholder="Enter Pill dose" value="<?= $rowPill['Pill_dose'] ?>">
            <input type="time" name="time" placeholder="Enter Pill time" value="<?= $rowPill['Pill_time'] ?>">
            <input type="submit" name="submit" value="Update" class="form-btn">
        </form>
    </div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connect);
?>
