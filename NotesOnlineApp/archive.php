<?php
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

// Get the user ID
$user_id = getUserId($conn, $username);

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

// Check if the archive action is performed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["note_id"])) {
    $note_id = $_POST["note_id"];

    // Insert the note into the archive table
    $sql = "INSERT INTO archive2_tbl (user_id, note_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $note_id);

    if ($stmt->execute()) {
        // Note archived successfully
        echo "Note archived successfully";
    } else {
        // Failed to archive note
        echo "Error archiving note: " . $conn->error;
    }

    // Redirect back to the dashboard
    // header("Location: dashboard.php");
    // exit();
}

// Check if the unarchive action is performed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["unarchive_note_id"])) {
    $note_id = $_POST["unarchive_note_id"];

    // Remove the note from the archive table
    $sql = "DELETE FROM archive2_tbl WHERE user_id = ? AND note_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $note_id);

    if ($stmt->execute()) {
        // Note unarchived successfully
        header("Location: archive.php"); // Redirect back to the dashboard
        exit();
    } else {
        // Failed to unarchive note
        echo "Error unarchiving note: " . $conn->error;
    }
}
// Retrieve archived notes for the logged-in user from archive2_tbl along with their titles
$sql_archived_notes = "SELECT n.note_id, n.title, n.content, n.created_at FROM notes_tbl n INNER JOIN archive2_tbl a ON n.note_id = a.note_id WHERE a.user_id = ?";

$stmt_archived_notes = $conn->prepare($sql_archived_notes);
$stmt_archived_notes->bind_param("i", $user_id);
$stmt_archived_notes->execute();
$result_archived_notes = $stmt_archived_notes->get_result();


// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="dashboardd.css"> <!-- Include dashboard CSS -->
</head>
<body>
    
<div class="main">
    <div class="menu">
        <h1 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h1>
        <a href="dashboardd.php"><i class="fa fa-sticky-note-o"></i> All Notes</a>
        <a href="favorites.php"><i class="fa fa-star"></i> Favorites</a>  
        <a href="archive.php"><i class="fa fa-archive"></i> Archives</a>
        <a href="index.php" onclick="openPopup()"><i class="fa fa-sign-out"></i> Logout</a>
        <p style="position: absolute; bottom: 20px; left: 50px; margin: 0; font-size: 20px;">Hi! Welcome, <br>
    </div> 

    <div class="archive">
        <h1>Archives</h1>
    </div>

    <div class="wrapper">
        <?php
        // Check if there are any archived notes
        if ($result_archived_notes->num_rows > 0) {
            // Loop through each archived note and display it
            while ($row = $result_archived_notes->fetch_assoc()) {
                ?>
                <div class="note-box">
                    <!-- Note content -->
                    <div class="note">
                        <p><?php echo $row["title"]; ?></p>
                        <div><hr class="underline"></div>
                        <span><?php echo $row["content"]; ?></span>
                    </div>
                    <div class="bottom-content">
                        <span class="date"><?php echo date("F j, Y", strtotime($row["created_at"])); ?></span>
                        <!-- Add any other necessary actions/buttons for archived notes -->
                        <!-- Unarchive button -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="unarchive_note_id" value="<?php echo $row["note_id"]; ?>">
                            <button type="submit">Unarchive</button>
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            // Display a message if there are no archived notes
            echo "<p>No archived notes found.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>


<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');

    * {
        padding: 0px;
        margin: 0px;
        box-sizing: border-box;
        list-style: none;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: white;
    }

    .main {
        width: 100%;
        height: 100vh;
        display: flex;
    }

    .menu {
        padding-top: 20px;
        width: 15%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        background-color: pink;
    }

    .menu img {
        position: absolute;
        top: 80%;
        right: 85px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    #notelt-title span.do {
        color: rgb(233, 109, 130);
    }

    #notelt-title span.note {
        color: black;
    }

    .menu a {
        font-size: 18px;
        padding: 10px;
        top: 10px;
        text-decoration: none;
        color: black;
        display: block;
    }

    .menu a i {
        margin-right: 5px;
    }

    .menu a:hover {
        background-color: white;
    }

    .archive {
        position: fixed;
        top: 0;
        background: pink;
        width: 100%;
        z-index: 1;
    }

    .archive h1 {
        font-size: 40px;
        position: fixed;
        left: 220px;
        margin-top: 25px;
    }

    .wrapper {
        margin-top: 100px; /* Adjust top margin as needed */
        margin-left: 250px; /* Increase the margin to create space for the sidebar/menu */
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: flex-start;
        align-items: flex-start;
    }

    .note-box {
        height: 250px;
        width: 265px;
        background: #fff;
        border-radius: 5px;
        padding: 15px 20px 20px;
        margin-left: 250px;
        margin-top: 20px;
        border: 2px solid pink;
    }

    .note p {
        font-size: 20px;
        font-weight: bold;
        text-align: left;
        margin: 0;
    }

    .underline {
        border: none;
        border-top: 1px solid pink;
        width: 100%;
        margin-top: 1px;
    }

    .note span {
        font-size: 16px;
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        text-align: left;
    }

    .date {
        font-size: 16px;
        color: black;
        position: absolute;
        bottom: 5px;
        left: 10px;
    }
</style>
