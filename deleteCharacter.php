<?php
// Initialize the delete message variable
$deleteMessage = '';

// Create a database connection
$con = new mysqli('localhost', 'student_2301', 'pass2301', 'student_2301');

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check and set $_POST variables
    $characterToDelete = isset($_POST['characterToDelete']) ? $_POST['characterToDelete'] : '';

    // Validate and sanitize input
    $characterToDelete = mysqli_real_escape_string($con, $characterToDelete);

    // Use prepared statement to prevent SQL injection
    $stmt = $con->prepare("DELETE FROM characters WHERE character_name = ?");
    $stmt->bind_param("s", $characterToDelete);

    if ($stmt->execute()) {
        $deleteMessage = "Character deleted successfully";
    } else {
        $deleteMessage = "Error deleting character: " . $stmt->error;
        // Log or handle the error as appropriate
    }

    $stmt->close();
}

$con->close();

// Pass the delete message as a URL parameter when redirecting
header("location: index.php?deleteMessage=" . urlencode($deleteMessage) . "#MyCharactersAndMissions");
exit(); // This is important to stop further execution
