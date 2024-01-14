<?php
$servername = "localhost";
$username = "student_2301";
$password = "pass2301";
$dbname = "student_2301";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$characterMessage = "";
$characterDetails = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $character_name = isset($_POST["character_name"]) ? $_POST["character_name"] : '';

    // Check if the "Find" button is clicked
    if (isset($_POST["findCharacter"])) {
        $character_name = mysqli_real_escape_string($conn, $character_name);

        $stmt = $conn->prepare("SELECT * FROM characters WHERE character_name = ?");
        $stmt->bind_param("s", $character_name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $characterMessage = "Your character is registered.";
            $characterDetails = "Name: " . $row["character_name"] . "<br>";
            $characterDetails .= "Power: " . $row["character_power"] . "<br>";
            $characterDetails .= "Health: " . $row["character_health"] . "<br>";
            $characterDetails .= "Clan: " . $row["clan_name"] . "<br>";
            $characterDetails .= "Level: " . $row["character_level"] . "<br>";
            $characterDetails .= "Class: " . $row["character_class"] . "<br>";
            $characterDetails .= "Description: " . $row["character_description"];
        } else {
            $characterMessage = "Character is not registered.";
        }

        $stmt->close();

        // Scroll to the section after form submission
        echo '<script>window.location.hash = "#MyCharactersAndMissions";</script>';
    } elseif (isset($_POST["deleteCharacter"])) {
        $character_name = mysqli_real_escape_string($conn, $character_name);

        $stmt = $conn->prepare("DELETE FROM characters WHERE character_name = ?");
        $stmt->bind_param("s", $character_name);

        if ($stmt->execute()) {
            $characterMessage = "Character deleted successfully";
        } else {
            $characterMessage = "Error deleting character: " . $stmt->error;
        }

        $stmt->close();
    }
}
$conn->close();
?>
