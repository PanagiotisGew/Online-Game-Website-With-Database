<?php
$servername = "localhost";
$username = "student_2301";
$password = "pass2301";
$dbname = "student_2301";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$missionMessage = "";
$missionDetails = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mission_id = isset($_POST["mission_id"]) ? $_POST["mission_id"] : '';
    $mission_id = mysqli_real_escape_string($conn, $mission_id);
    if (isset($_POST["deleteMission"])) {
        $stmt = $conn->prepare("DELETE FROM missions WHERE mission_name = ?");
        $stmt->bind_param("s", $mission_id);

        if ($stmt->execute()) {
            $missionMessage = "Mission deleted successfully";
        } else {
            $missionMessage = "Error deleting mission: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $stmt = $conn->prepare("SELECT * FROM missions WHERE mission_name = ?");
        $stmt->bind_param("s", $mission_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $missionMessage = "Your mission is registered.";
            $missionDetails = "Name: " . $row["mission_name"] . "<br>";
            $missionDetails .= "Level: " . $row["mission_level"] . "<br>";
            $missionDetails .= "Days: " . $row["mission_days"] . "<br>";
            $missionDetails .= "Rewards: " . $row["mission_rewards"] . "<br>";
            $missionDetails .= "Terrain ID: " . $row["terr_id"] . "<br>";
            $missionDetails .= "Mission Details: " . $row["mission_description"];
        } else {
            $missionMessage = "Mission is not registered.";
        }
        $stmt->close();
        
        // Scroll to the section after form submission
        echo '<script>window.location.hash = "#MyCharactersAndMissions";</script>';
    }
}

$conn->close();
?> 