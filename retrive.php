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

// Check if the user is logged in
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

// Check if the form for updating a note is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"]) && isset($_POST["content"]) && isset($_POST["note_id"])) {
    // Get form data
    $title = $_POST["title"];
    $content = $_POST["content"];
    $note_id = $_POST["note_id"];
    
    // Update the note in the database
    $sql = "UPDATE notes_tbl SET title = ?, content = ? WHERE note_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $content, $note_id, $user_id);
    
    if ($stmt->execute()) {
        // Note updated successfully
        // Redirect back to the dashboard after updating the note
        header("Location: dashboardd.php");
        exit();
    } else {
        // Failed to update note
        echo "Error updating note: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>


// Fetch notes for the logged-in user in reverse order
    $notes = [];
    while ($row = $result->fetch_assoc()) {
        array_unshift($notes, $row); // Insert each note at the beginning of the array
    }