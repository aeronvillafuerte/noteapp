<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to login page or handle unauthorized access
    exit("Unauthorized access");
}

// Database connection configuration
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "noteapp";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID
$username = $_SESSION["username"];
$sql = "SELECT user_id FROM logintbl WHERE user_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$user_id = $row["user_id"];

// Check if note_id is provided
if (isset($_POST["note_id"])) {
    $note_id = $_POST["note_id"];

    // Check if the note is not already in favorites
    $check_sql = "SELECT * FROM favorites_tbl WHERE user_id = ? AND note_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $note_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        // Insert note into favorites
        $insert_sql = "INSERT INTO favorites_tbl (user_id, note_id) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $user_id, $note_id);
        if ($insert_stmt->execute()) {
            echo "Note added to favorites successfully";
        } else {
            echo "Error adding note to favorites: " . $conn->error;
        }
    } else {
        echo "Note is already in favorites";
    }
} else {
    echo "Note ID not provided";
}

// Close connection
$conn->close();
?>
