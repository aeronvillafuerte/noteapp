<?php
// Start the session to access session variables
session_start();

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

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION["username"])) {
    // Handle unauthenticated access
    echo "Unauthorized access";
    exit;
}

// Retrieve the logged-in user's username from the session
$username = $_SESSION["username"];

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

// Get the user ID
$user_id = getUserId($conn, $username);

// Check if the form is submitted and note_id is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["note_id"])) {
    $note_id = $_POST["note_id"];

    // Delete the note from the database
    $sql = "DELETE FROM notes_tbl WHERE note_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $note_id, $user_id);

    if ($stmt->execute()) {
        echo "Note deleted successfully";
    } else {
        echo "Error deleting note: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>
