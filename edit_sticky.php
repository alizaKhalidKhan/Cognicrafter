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
    header("Location: c_sticky.php");
    exit();
}

$stickyId = $_GET['id'];

// Fetch pill information based on the Pill ID
$select = "SELECT * FROM stickynotes WHERE StickyNotes_id = '$stickyId'";
$result= mysqli_query($connect, $select);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Note not found.";
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated note information from the form
    $title = mysqli_real_escape_string($connect, $_POST['name']);
    $desc = mysqli_real_escape_string($connect, $_POST['desc']);

    // Update the note information in the database
    $update = "UPDATE stickynotes SET
                Sticky_title = '$title',
                Sticky_description = '$desc'
            WHERE StickyNotes_id = '$stickyId'";


    if (mysqli_query($connect, $update)) {
        header("Location: c_sticky.php");
        exit();
    } else {
        echo "Error updating sticky information: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CogniCrafter- Sticky Notes</title>
    
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

    <div class="form-container" style="background-color: white;">
        <form action="" method="post">
            <h2 class="mb-3">Edit Notes</h2>
            <input type="text" name="name" required placeholder="Enter Title" value="<?= $row['Sticky_title'] ?>">
             <input type="text" name="desc" placeholder="Enter Description" value="<?= $row['Sticky_description'] ?>">
            <input type="submit" name="submit" value="Update" class="form-btn">
        </form>
    </div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connect);
?>
