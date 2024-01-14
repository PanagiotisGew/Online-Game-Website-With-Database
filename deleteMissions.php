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
    $deleteTable = isset($_POST['deleteTable']) ? $_POST['deleteTable'] : '';

    // Validate and sanitize input
    $deleteTable = mysqli_real_escape_string($con, $deleteTable);

    // Check if the form was submitted to delete the table
    if ($deleteTable === 'missions') {
        // Use prepared statement to prevent SQL injection
        $stmt = $con->prepare("DROP TABLE missions");

        if ($stmt->execute()) {
            $deleteMessage = "Table 'missions' deleted successfully";
        } else {
            $deleteMessage = "Error deleting table 'missions': " . $stmt->error;
            // Log or handle the error as appropriate
        }

        $stmt->close();
    } else {
        $deleteMessage = "Invalid request to delete table";
    }
}

$con->close();

// Pass the delete message as a URL parameter when redirecting
header("location: index.php?deleteMessage=" . urlencode($deleteMessage));
exit(); // This is important to stop further execution
?>
