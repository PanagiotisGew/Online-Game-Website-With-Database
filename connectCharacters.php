<?php
// Initialize the message variable
$message = '';

// Create a database connection
$con = new mysqli('localhost', 'student_2301', 'pass2301', 'student_2301');

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Λήψη των τιμών POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check and set $_POST variables
    $character_name = isset($_POST['character_name']) ? $_POST['character_name'] : '';
    $character_power = isset($_POST['character_power']) ? $_POST['character_power'] : '';
    $character_health = isset($_POST['character_health']) ? $_POST['character_health'] : '';
    $clan_name = isset($_POST['clan_name']) ? $_POST['clan_name'] : '';
    $character_level = isset($_POST['character_level']) ? $_POST['character_level'] : '';
    $character_class = isset($_POST['character_class']) ? $_POST['character_class'] : '';
    $character_description = isset($_POST['character_description']) ? $_POST['character_description'] : '';

    // Validate and sanitize input
    $character_name = mysqli_real_escape_string($con, $character_name);
    $character_power = mysqli_real_escape_string($con, $character_power);
    $character_health = mysqli_real_escape_string($con, $character_health);
    $character_level = mysqli_real_escape_string($con, $character_level);
    $character_class = mysqli_real_escape_string($con, $character_class);
    $character_description = mysqli_real_escape_string($con, $character_description);

    // Check if the selected character class is a valid value
    $valid_classes = array('Fighter', 'Assassin', 'Wizard', 'Dwarf', 'Priest');
    if (!in_array($character_class, $valid_classes)) {
        $message = "Error: Invalid character class";
    } else {
        // Check if 'No Clan' already exists in the clan table
        $check_clan_stmt = $con->prepare("SELECT clan_name FROM clan WHERE clan_name = ?");
        $check_clan_stmt->bind_param("s", $clan_name);
        $check_clan_stmt->execute();
        $check_clan_stmt->store_result();

        if ($check_clan_stmt->num_rows === 0) {
            // 'No Clan' does not exist, insert it
            $insert_clan_stmt = $con->prepare("INSERT INTO clan (clan_name) VALUES (?)");
            $insert_clan_stmt->bind_param("s", $clan_name);
            $insert_clan_stmt->execute();
            $insert_clan_stmt->close();
        }

        $check_clan_stmt->close();

        // Check if a record with the same character name already exists
        $check_stmt = $con->prepare("SELECT character_name FROM characters WHERE character_name = ?");
        $check_stmt->bind_param("s", $character_name);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "Error: Character with the same name already exists";
        } else {
            // Use prepared statement to prevent SQL injection
            $stmt = $con->prepare("INSERT INTO `characters` (character_name, character_power, character_health, clan_name, character_level, character_class, character_description) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siisiss", $character_name, $character_power, $character_health, $clan_name, $character_level, $character_class, $character_description);

            if ($stmt->execute()) {
                $message = "Character inserted successfully";
            } else {
                $message = "Error inserting data: " . $stmt->error;
                // Log or handle the error as appropriate
            }

            $stmt->close();
        }

        $check_stmt->close();
    }
}

$con->close();

// Pass the message as a URL parameter when redirecting
echo '<script>window.location.href = "index.php?message=' . urlencode($message) . '#characters";</script>';
exit(); // This is important to stop further execution
?>
