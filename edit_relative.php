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
    header("Location: c_relative.php");
    exit();
}

$relativeId = $_GET['id'];

// Fetch relative information based on the relative ID
$selectRelative = "SELECT * FROM relative WHERE Relative_id = '$relativeId'";
$resultRelative = mysqli_query($connect, $selectRelative);

if (!$resultRelative || mysqli_num_rows($resultRelative) === 0) {
    echo "Relative not found.";
    exit();
}

$rowRelative = mysqli_fetch_assoc($resultRelative);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated relative information from the form
    $relativeName = mysqli_real_escape_string($connect, $_POST['name']);
    $relativePhone = mysqli_real_escape_string($connect, $_POST['phone']);
    $relativeEmail = mysqli_real_escape_string($connect, $_POST['email']);
    $relativeAddress = mysqli_real_escape_string($connect, $_POST['address']);

    // Update the relative information in the database
    $updateRelative = "UPDATE relative SET
                        Relative_name = '$relativeName',
                        Relative_phone = '$relativePhone',
                        Relative_email = '$relativeEmail',
                        Relative_address = '$relativeAddress'
                      WHERE Relative_id = '$relativeId'";

    if (mysqli_query($connect, $updateRelative)) {
        header("Location: c_relative.php");
        exit();
    } else {
        echo "Error updating relative information: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CogniCrafter- Relative Information</title>
    
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
            <h2 class="mb-3">Edit Relative Information</h2>
            <input type="text" name="name" required placeholder="Enter Relative name" value="<?= $rowRelative['Relative_name'] ?>">
            <input type="text" name="phone" required placeholder="Enter Relative phone" value="<?= $rowRelative['Relative_phone'] ?>">
            <input type="text" name="email" required placeholder="Enter Relative email" value="<?= $rowRelative['Relative_email'] ?>">
            <input type="text" name="address" required placeholder="Enter Address" value="<?= $rowRelative['Relative_address'] ?>">
            <input type="submit" name="submit" value="Update" class="form-btn">
        </form>
    </div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connect);
?>
