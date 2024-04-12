<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Handle unauthenticated access
    echo "Unauthorized access";
    exit;
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

// Retrieve the logged-in user's username from the session
$username = $_SESSION["username"];

// Get the user ID
$user_id = getUserId($conn, $username);

// Check if the note ID is provided in the request
if (isset($_POST["note_id"])) {
    $note_id = $_POST["note_id"];
    
    // Remove the note from favorites
    $sql = "DELETE FROM favorites_tbl WHERE user_id = ? AND note_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $note_id);
    if ($stmt->execute()) {
        echo "Note removed from favorites successfully";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Note ID not provided";
}

// Close connection
$conn->close();

// Function to get user_id from username
function getUserId($conn, $username) {
    $sql = "SELECT user_id FROM logintbl WHERE user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["user_id"];
    } else {
        return null; // User not found
    }
}
?>
