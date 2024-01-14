<?php

// Initialize the message variable
$message = '';

// Λήψη των τιμών POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check and set $_POST variables
    $mission_name = isset($_POST['mission_name']) ? $_POST['mission_name'] : '';
    $mission_level = isset($_POST['mission_level']) ? $_POST['mission_level'] : '';
    $mission_days = isset($_POST['mission_days']) ? $_POST['mission_days'] : '';
    $mission_rewards = isset($_POST['mission_rewards']) ? $_POST['mission_rewards'] : '';
    $terr_id = isset($_POST['terr_id']) ? $_POST['terr_id'] : '';
    $mission_description = isset($_POST['mission_description']) ? $_POST['mission_description'] : '';

    // Create a database connection
    $con = new mysqli('localhost', 'student_2301', 'pass2301', 'student_2301');

    // Validate and sanitize input
    $mission_name = mysqli_real_escape_string($con, $mission_name);
    $mission_level = mysqli_real_escape_string($con, $mission_level);
    $mission_days = mysqli_real_escape_string($con, $mission_days);
    $mission_rewards = mysqli_real_escape_string($con, $mission_rewards);
    $terr_id = mysqli_real_escape_string($con, $terr_id);
    $mission_description = mysqli_real_escape_string($con, $mission_description);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Check if the specified terr_id exists in the terrain table
    $check_terr_stmt = $con->prepare("SELECT terrain_id FROM terrain WHERE terrain_id = ?");
    $check_terr_stmt->bind_param("s", $terr_id);
    $check_terr_stmt->execute();
    $check_terr_stmt->store_result();

    if ($check_terr_stmt->num_rows === 0) {
        $message = "Error: Terrain ID does not exist";
    } else {
        // Check if a record with the same mission_name already exists
        $check_stmt = $con->prepare("SELECT terr_id FROM missions WHERE terr_id = ?");
        $check_stmt->bind_param("s", $terr_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "Error: Mission with the same terrain ID already exists";
        } else {
            // Use prepared statement to prevent SQL injection
            $stmt = $con->prepare("INSERT INTO `missions` (mission_name, mission_level, mission_days, mission_rewards, terr_id, mission_description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siisss", $mission_name, $mission_level, $mission_days, $mission_rewards, $terr_id, $mission_description);

            if ($stmt->execute()) {
                $message = "Your mission has been created";
            } else {
                $message = "Error inserting data";
                // Log or handle the error as appropriate
            }

            $stmt->close();
        }
    }

    $check_terr_stmt->close();

    // Close $check_stmt only if it is set and not null
    if (isset($check_stmt)) {
        $check_stmt->close();
    }

    $con->close();

    // Pass the message as a URL parameter when redirecting
    header("location: index.php?message2=" . urlencode($message) . "#missions");
    exit(); // This is important to stop further execution
}

?>
