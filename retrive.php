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

    function toggleStar(element, noteId, isFavorite) {
    if (!element.classList.contains("starred")) {
        // Star is not filled, so fill it
        element.classList.add("starred");
        element.classList.remove("fa-star");
        element.classList.add("fa-star-o");
        if (!isFavorite) {
            addToFavorites(noteId); // Call function to add note to favorites if it's not already there
        }
    } else {
        // Star is filled, so unfill it
        element.classList.remove("starred");
        element.classList.remove("fa-star");
        element.classList.add("fa-star-o");
        if (isFavorite) {
            removeFromFavorites(noteId); // Call function to remove note from favorites if it's already there
        }
    }
}

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

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION["username"])) {
    // Handle unauthenticated access
    echo "Unauthorized access";
    exit;
}

// Retrieve the logged-in user's username from the session
$username = $_SESSION["username"];

// Get the user ID
$user_id = getUserId($conn, $username);

// Check if the form for adding a note is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"]) && isset($_POST["content"])) {
    // Get form data
    $title = $_POST["title"];
    $content = $_POST["content"];
    
    // Insert the note into the database
    $sql = "INSERT INTO notes_tbl (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $title, $content);
    
    if ($stmt->execute()) {
        // Note inserted successfully
        // Redirect back to the dashboard after adding the note
        header("Location: dashboardd.php");
        exit();
    } else {
        // Failed to insert note
        echo "Error: " . $conn->error;
    }
}

// Check if the form for deleting a note is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["note_id"])) {
    $note_id = $_POST["note_id"];

    // Delete the note from the database
    $sql = "DELETE FROM notes_tbl WHERE note_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $note_id, $user_id);

    if ($stmt->execute()) {
        // Note deleted successfully
        // Redirect back to the dashboard after deleting the note
        header("Location: dashboardd.php");
        exit();
    } else {
        echo "Error deleting note: " . $conn->error;
    }
}

// After archiving the note, move it to archive2_tbl
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
    header("Location: dashboardd.php");
    exit();
}


// Fetch notes for the logged-in user
$sql = "SELECT * FROM notes_tbl WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user's profile picture path
$sql_profile_picture = "SELECT profile_picture FROM logintbl WHERE user_name = ?";
$stmt_profile_picture = $conn->prepare($sql_profile_picture);
$stmt_profile_picture->bind_param("s", $username);
$stmt_profile_picture->execute();
$result_profile_picture = $stmt_profile_picture->get_result();
$row_profile_picture = $result_profile_picture->fetch_assoc();
$profile_picture = $row_profile_picture["profile_picture"];

// Fetch notes for the logged-in user that are not archived
$sql = "SELECT * FROM notes_tbl WHERE user_id = ? AND note_id NOT IN (SELECT note_id FROM archive2_tbl WHERE user_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch notes for the logged-in user including favorites
$sql = "SELECT notes_tbl.note_id, notes_tbl.title, notes_tbl.content, notes_tbl.created_at, favorites_tbl.user_id AS favorite_user_id
        FROM notes_tbl
        LEFT JOIN favorites_tbl ON notes_tbl.note_id = favorites_tbl.note_id AND favorites_tbl.user_id = ?
        WHERE notes_tbl.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
// Close connection
$conn->close();
?>
         <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard</title>
            <link rel="stylesheet" href="dashboardd.css"> 
            <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

            <script>
        function archiveNote(noteId) {
    if (confirm("Are you sure you want to archive this note?")) {
        // Send AJAX request to archive.php
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Reload the page after archiving the note
                window.location.reload();
            }
        };
        xhttp.open("POST", "archive.php", true); // Update the URL to archive.php
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("note_id=" + noteId);
    }
}
    </script>
        </head>
        <body>

        <div class="main">
            <div class="menu">
                <h1 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h1>
                <a href="dashboardd.php"><i class="fa fa-sticky-note-o"></i> All Notes</a>
                <a href="favorites.php"><i class="fa fa-star"></i> Favorites</a>  
                <a href="archive.php"><i class="fa fa-archive"></i> Archives</a>
                <a href="index.php" onclick="openPopup()"><i class="fa fa-sign-out"></i> Logout</a>

                <img src="<?php echo $profile_picture; ?>" alt="Profile Picture">
                <p style="position: absolute; bottom: 20px; left: 35px; margin: 0; font-size: 20px; text-align: center;">Hi! Welcome, <br> <?php echo $username; ?></p> <!-- Display the logged-in user's name -->
            </div> 

            <div class="all-notes">
                <h1>All Notes</h1>
            </div>

            <div class="wrappper">
            <!-- Your content here -->
            <div class="search-container">
                <form>
                    <input type="text" name="" placeholder="Search...">
                    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>

            <div class="popup-box">
                <div class="popup">
                    <div class="content">
                        <header>
                            <p>Add a new Note</p>
                            <i class="uil uil-times"></i>
                        </header>
                        <form action="dashboardd.php" method="POST"> <!-- Corrected action attribute -->
                            <div class="row title">
                                <label>Title</label>
                                <input type="text" name="title"> <!-- Added name attribute -->
                            </div>
                            <div class="row description">
                                <label>Description</label>
                                <textarea name="content"></textarea> <!-- Added name attribute -->
                            </div>
                            <button type="submit">Add Note</button> <!-- Changed to type="submit" -->
                        </form>
                    </div>
                </div>
            </div>

            <div class="edit-popup-box">
                <div class="edit-popup">
                    <div class="contentt">
                    <header>
                        <p id="edit-popup-title">Update a Note</p>
                        <i class="uil uil-times" onclick="closeEditPopup()"></i>
                    </header>
                        <form id="edit-note-form" action="" method="POST">
                <div class="row title">
                        <label>Title</label>
                        <input type="text" name="title" id="edit-title">
                </div>
                <div class="row description">
                        <label>Description</label>
                        <textarea name="content" id="edit-content"></textarea>
                </div>
                        <input type="hidden" name="note_id" id="edit-note-id">
                        <button id="update-submit-btn">Update Note</button>
                     </form>
                    </div>
                </div>
            </div>

            <div class="wrapper">
        <li class="add-box">
            <div class="icon"><i class="uil uil-plus"></i></div>
            <p>Add new note</p>
        </li>
        
        <?php
    // Fetch notes for the logged-in user in reverse order
    $notes = [];
    while ($row = $result->fetch_assoc()) {
        array_unshift($notes, $row); // Insert each note at the beginning of the array
    }
 foreach ($notes as $row) { ?>

        
        <li class="note-box" id="note-<?php echo $row['note_id']; ?>">


            <!-- Note content -->
            <div class="note">
                <p><?php echo $row["title"]; ?></p>
                <div><hr class="underline"></div>
                <span class="content"><?php echo $row["content"]; ?></span>
            </div>

            
                <div class="bottom-content">
                <span class="date">
    <?php echo date("F j, Y", strtotime($row["created_at"])); ?>
   
 
    <i class="fa fa-star clickable-star" style="margin-left: 30px; font-size: 20px;" data-note-id="<?php echo $row['note_id']; ?>" onclick="toggleStar(this, <?php echo $row['note_id']; ?>)"></i>




</span>
                    <div class="settings" onclick="showMenu(this)">
                        <button>
                            <i class="uil uil-ellipsis-v"></i>
                        </button>
                        <ul class="menu">
                        <li>
                <i class="uil uil-edit" onclick="showEditPopup(<?php echo $row['note_id']; ?>, '<?php echo addslashes($row['title']); ?>', '<?php echo addslashes($row['content']); ?>')"></i> Edit
            </li>
                            <li>
                                <i class="uil uil-trash-alt" onclick="deleteNote(<?php echo $row['note_id']; ?>)"></i> Delete
                            </li>
                            <li onclick="archiveNote(<?php echo $row['note_id']; ?>)">
        <i class="uil uil-archive"></i> Archive
             </li>
                    </div>
                </div>
            </li>
        <?php } ?>
        
        </div>

            <div class="notes-wrapper"> <!-- New wrapper for notes -->
                <ul class="notes-list"> <!-- List to contain notes -->
                    <!-- Notes will be dynamically added here -->
                </ul>
            </div>
        </div>

        <script src="script.js"></script>
        <script>
window.onload = function() {
    // Loop through all the starred notes and update their appearance
    var starredNotes = document.querySelectorAll(".clickable-star");
    starredNotes.forEach(function(note) {
        var noteId = note.getAttribute("data-note-id");
        // Check if the note is favorited in localStorage
        if (localStorage.getItem("note_" + noteId + "_starred") === "true") {
            note.classList.add("starred"); // Add the 'starred' class to the element
            note.classList.remove("fa-star"); // Remove the 'fa-star' class
            note.classList.add("fa-star-o"); // Add the 'fa-star-o' class
        }
    });
};

function toggleStar(element, noteId, isFavorite) {
    if (!element.classList.contains("starred")) {
        // Star is not filled, so fill it
        element.classList.add("starred");
        element.classList.remove("fa-star");
        element.classList.add("fa-star-o");
        addToFavorites(noteId); // Call function to add note to favorites
        // Optionally, you can remove the note from the favorites dashboard immediately if it's already there
        var favoriteNoteElement = document.getElementById("favorite-note-" + noteId);
        if (favoriteNoteElement) {
            favoriteNoteElement.remove();
        }
        // Store the state of the star in local storage
        localStorage.setItem("note_" + noteId + "_starred", "true");
    } else {
        // Star is filled, so unfill it
        element.classList.remove("starred");
        element.classList.remove("fa-star-o");
        element.classList.add("fa-star");
        removeFromFavorites(noteId); // Call function to remove note from favorites
        // Optionally, you can remove the corresponding note element from the favorites list in the DOM
        var noteElement = document.getElementById("note-" + noteId);
        if (noteElement) {
            noteElement.remove();
        }
        // Remove the state of the star from local storage
        localStorage.removeItem("note_" + noteId + "_starred");
    }
}



function addToFavorites(noteId) {
    // Send AJAX request to add the note to favorites
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Handle success or display any messages
            console.log(this.responseText); // Log response for debugging
        }
    };
    xhttp.open("POST", "add_to_favorites.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("note_id=" + noteId);
}


function removeFromFavorites(noteId) {
    // Send AJAX request to remove the note from favorites
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Handle success or display any messages
            console.log(this.responseText); // Log response for debugging
            // Optionally, you can remove the corresponding note element from the favorites list in the DOM
            var noteElement = document.getElementById("note-" + noteId);
            if (noteElement) {
                noteElement.remove();
            }
        }
    };
    xhttp.open("POST", "remove_from_favorites.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("note_id=" + noteId);
}



        </script>

        </body>
        </html>


