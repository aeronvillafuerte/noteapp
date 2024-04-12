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

    </script>
    
        </head>
        <body>

        <div class="main">
            <div class="menu">
                <h1 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h1>
                <a href="dashboardd.php"><i class="fa fa-sticky-note-o"></i> All Notes</a>
                <a href="favorites.php"><i class="fa fa-star"></i> Favorites</a>  
                <a href="archive.php"><i class="fa fa-archive"></i> Archives</a>
                <a href="#" id="logoutBtn"><i class="fa fa-sign-out"></i> Logout</a>

                <img src="<?php echo $profile_picture; ?>" alt="Profile Picture">
                <p style="position: absolute; bottom: 20px; left: 35px; margin: 0; font-size: 20px; text-align: center;">Hi! Welcome, <br> <?php echo $username; ?></p> <!-- Display the logged-in user's name -->
            </div> 

            <div class="all-notes">
                <h1>All Notes</h1>
            </div>

 
    <!-- Logout form with JavaScript confirmation -->
    <form method="post" id="logoutForm">
        <input type="submit" name="logout" value="Logout" onclick="return confirmLogout();">
    </form>

    <!-- JavaScript function for confirmation -->
    <script>
        function confirmLogout() {
            return confirm("Are you sure you want to logout?");
        }
    </script>
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
            // Find the star in the dashboard and turn it black
            var star = document.querySelector(".clickable-star[data-note-id='" + noteId + "']");
            if (star) {
                star.classList.remove("starred");
                star.classList.remove("fa-star-o");
                star.classList.add("fa-star");
            }
        }
    };

    xhttp.open("POST", "remove_from_favorites.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("note_id=" + noteId);
}


document.getElementById("logoutBtn").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default link action

    // Display a confirmation dialog
    var logoutConfirmed = confirm("Are you sure you want to logout?");

    // If the user confirms logout
    if (logoutConfirmed) {
        // Clear session and redirect to logout.php
        window.location.href = "logout.php";
    }
});


        </script>


        </body>
        </html>


    
        <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');

        *{
            padding: 0px;
            margin: 0px;
            box-sizing: border-box;
            list-style: none;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px; 
        }

        .main{
            width: 100%;
            height: 100vh;
            display: flex;
        }

        .menu{
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
            position: absolute; /* Set the position to absolute */
            top: 80%; /* Adjust the distance from the top */
            right: 85px; /* Adjust the distance from the right */
            width: 50px;
            height: 50px;
            border-radius: 50%;
}

        #notelt-title span.do {
        color: rgb(233, 109, 130); /* Change color of "Do" to pink */
        }

        #notelt-title span.note {
        color: black; /* Change color of "Note" to pink */
        }

        .menu a{
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

        .menu a:hover{
            background-color: white;
        }

        .body {
            margin-left: 20px; /* Adjust the left margin */
            margin-right: auto;
            padding-top: 20px;
            font-size: 18px;
            text-align: left; /* Align the text to the left */
            color: black; /* Set the text color to black */
        }


        @import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');

        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: white;
    }

    .all-notes h1{
        font-size: 40px;
        position: fixed;
        left: 220px;
        margin-top: 25px;
    }

    .all-notes {
        position: fixed;
        top: 0;
        background: pink;
        width: 100%;
        z-index: 1; /* Ensure it's above the other content */
    }


    .note p {
        font-size: 20px;
        font-weight: bold;
        text-align: left;
        margin: 0;
        position: relative; /* Add this line */
}

    .note p i {
        position: absolute;
        top: 0;
        right: 0;
}

.note span {
    font-size: 16px;
    margin-top: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    text-align: left;
    white-space: nowrap; /* Prevent line breaks */
    overflow: hidden; /* Hide overflowing content */
    text-overflow: ellipsis; /* Display ellipsis for overflow */
}


    .clickable-star {
        cursor: pointer; /* Add pointer cursor to indicate clickability */
    }


    /* Define the style for the outlined star icon */
.fa-star-o {
    color: yellow; /* Set the color to black */
}

/* Define the style for the filled star icon */
.fa-star {
    color: black; /* Set the color to yellow */
}


    .wrappper {
        /* Your wrapper styles */
        flex: 10; /* Allow content to take remaining space */
        justify-content: flex-end;
        position: fixed;
        top: 0;
        left: 900px;
        width: 20%
        padding: 20px;
        margin-top: 30px;
    }

    .search-container {
        height: 180px;
        text-align: center;
        position: fixed;
        top: 30px; /* Adjust as needed based on your layout */
        width: 200px;
        z-index: 1; /* Ensure it's above the other content */
    }

    .notes-wrapper {
        padding-top: 50px; /* Adjust as needed to prevent overlap */
    }

    .search-container form {
        background: pink;
        border-radius: 25px;
        position: relative;
        display: inline-block; /* Ensures the form width fits its contents */
    }

    .search-container input {
        display: block;
        border-radius: 25px;
        padding: 8px 40px 8px 20px;
        border: none;
        box-shadow: 0 3px 3px 3px black;
    }

    .search-container input:focus {
        outline: none;
    }

    .search-container button {
        position: absolute;
        top: 0;
        right: 0;
        width: 50px;
        height: 100%;
        border-radius: 50%;
        cursor: pointer;
        border: none;
        background: none;
        font-size: 18px;
    }

    .search-container button i {
        color: rgb(93, 94, 95);
    }

    .search-container button:hover i {
        color: rgb(162, 163, 163);
    }

    .wrapper {
        margin: 100px 20px; /* Add margin to the top and right */
        display: flex;
        flex-wrap: wrap; /* Wrap items to new row */
        gap: 20px; /* Reduce the gap */
        justify-content: flex-start; /* Align items to the left */
        align-items: flex-start; /* Align items to the top */
        margin-left: calc(15% + 20px); /* Adjust left margin to account for menu width and padding */
    }

    .wrapper li {
        height: 250px;
        width: 265px; /* Set width for each note */
        list-style: none;
        background: #fff;
        border-radius: 5px;
        padding: 15px 20px 20px;
        border: 2px solid pink;
    }

    .add-box, .icon, .settings .menu li, .popup, header {
        display: flex;
        flex-direction: row;
    align-items: center;
        justify-content: space-between;
    }

    .add-box {
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        cursor: pointer;
    }

    .add-box .icon {
        height: 78px;
        width: 78px;
        color: pink;
        font-size: 40px;
        border-radius: 50%;
        border: 2px dashed pink;
        justify-content: center;
    }

    .add-box p { 
        color: black;
        font-weight: 500;
        margin-top: 20px;
    }

    .note-box {
        position: relative; /* Ensure positioning context for absolute positioning */
        background: #fff;
        border-radius: 5px;
        padding: 15px 20px 20px;
        border: 2px solid pink;
        display: flex;
        flex-direction: column;
        align-items: center;
        
    }

    .note .content {
        font-size: 16px;
        margin-top: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        word-wrap: break-word; /* Ensure long words break properly */
}



    .note {
        width: 100%;
    }

    .note p {
        font-size: 20px;
        font-weight: bold;
        text-align: left;
        margin: 0;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .note p i {
        position: absolute;
        top: 0;
        right: 0;
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
        align-items: flex-start; /* Adjust alignment to start */
        position: relative;
        text-align: left;
        overflow: hidden; /* Hide overflowing content */
        white-space: normal; /* Allow line breaks */
    }

    .date {
        font-size: 16px;
        color: black;
        position: absolute;
        bottom: 5px; /* Adjust position relative to the note */
        left: 10px;
    }

    .settings {
        position: absolute;
        bottom: 5px; /* Adjust position relative to the note */
        right: 5px;
        z-index: 1;
    }

    .settings button {
        width: 30px;
        height: 30px;
        background: #fff;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        outline: none;
        position: absolute;
        bottom: 0;
        right: 0;
    }

    .menu li{
        height: 30px;
        width: 100px; 
        padding: 10px;
        display: flex;
        align-items: center;
        background: pink;
        border: 1px solid;
        margin: 0;
    }

    .menu li i {
        margin-right: 5px; /* Adjust the spacing between the icon and text */
    }

    .settings.show .menu {
        transform: scale(1);
        bottom: 30px; /* Adjust position relative to the settings icon */
    }


    .settings .menu {
        position: absolute;
        bottom: 0; /* Reset position */
        right: 0;
        padding: 5px 0;
        background: #fff;
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        transform: scale(0);
        transition: transform 0.2s ease;
        transform-origin: bottom right;
    }

    .menu .settings:hover .menu {
        transform: scale(1);
    }

    .menu {
        padding-top: 20px;
        height: 100%;
        width: 15%;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        background-color: pink;
    }

    .menu li:hover {
        background-color: white !important; /* Increase specificity and use !important */
    }


    .menu .settings:hover .menu li {
        background: white;
    }

    .menu .settings .menu li {
        height: 30px;
        width: 100px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        background: pink;
        border: 1px solid pink;
    }

    .popup-box {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        z-index: 2;
        background: rgba(0, 0, 0, 0.14);
    }

    .popup-box .popup {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 3;
        max-width: 500px;
        width: 100%;
        justify-content: center;
        transform: translate(-50%, -50%);
    }

    .popup-box,
    .popup-box .popup {
        opacity: 0;
        pointer-events: none;
        transition: all 0.25s ease;
    }

    .popup-box.show,
    .popup-box.show .popup {
        opacity: 1;
        pointer-events: auto;
    }

    .popup .content {
        width: calc(100% - 15px);
        background: #fff;
        border-radius: 5px;
    }

    .popup .content header {
        padding: 15px 25px;
        border-bottom: 1px solid #CCC;
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .content header p {
        font-size: 20px;
        font-weight: 500;
        margin: 0;
    }

    .content header i {
        color: #8b8989;
        cursor: pointer;
        font-size: 23px;
        position: absolute; /* Set position to absolute */
        top: 50%; /* Move the icon vertically to the center */
        right: 25px; /* Align the icon to the right */
        transform: translateY(-50%); /* Adjust for vertical centering */

    }

    .content form {
        margin: 15px 25px 35px;
    }

    .content form :where(input, textarea) {
        width: 100%;
        height: 50px;
        font-size: 17px;
        padding: 0 15px;
        border-radius: 4px;
        border: 1px solid #999;
        outline: none;
    }

    .content form textarea {
        height: 150px;
        resize: none;
        padding: 8px 15px;
    }

    .content form button {
        width: 100%;
        height: 50px;
        background: pink;
        border: none;
        outline: none;
        cursor: pointer;
        color: black;
        border-radius: 4px;
        font-size: 17px;
    }

    .menu {
        padding-top: 20px;
        height: 100%;
        width: 15%;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        background-color: pink;
    }

    .edit-popup-box {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        z-index: 2;
        background: rgba(0, 0, 0, 0.14);
        opacity: 0; /* Initially hidden */
        pointer-events: none; /* Initially not clickable */
        transition: opacity 0.25s ease; /* Smooth transition for opacity */
    }

    .edit-popup-box .edit-popup {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 3;
        max-width: 500px;
        width: 100%;
        justify-content: center;
        transform: translate(-50%, -50%);
        opacity: 0;
        pointer-events: none;
        transition: all 0.25s ease;
    }

    /* Hide the edit popup box initially */
    .edit-popup-box:not(.show) .edit-popup {
        display: none;
    }

    .edit-popup-box.show,
    .edit-popup-box.show .edit-popup {
        opacity: 1;
        pointer-events: auto;
    }

    .edit-popup .contentt {
        width: calc(103% - 15px);
        background: #fff;
        border-radius: 5px;
    }

    .edit-popup .contentt header {
        padding: 15px 25px;
        border-bottom: 1px solid #CCC;
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .contentt header p {
        font-size: 20px;
        font-weight: 500;
        margin: 0;
    }


    .edit-popup {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 3;
        max-width: 500px;
        width: 100%;
        justify-content: center;
        transform: translate(-50%, -50%);
        background: #fff;
        border-radius: 5px;
    }

    .contentt header i {
        color: #8b8989;
        cursor: pointer;
        font-size: 23px;
        position: absolute; 
        top: 50%;
        right: 25px; 
        transform: translateY(-50%);
    }

    .contentt form {
        margin: 15px 25px 35px;
    }

    .contentt form :where(input, textarea) {
        width: 100%;
        height: 50px;
        font-size: 17px;
        padding: 0 15px;
        border-radius: 4px;
        border: 1px solid #999;
        outline: none;
    }

    .contentt form textarea {
        height: 150px;
        resize: none;
        padding: 8px 15px;
    }

    .contentt form button {
        width: 100%;
        height: 50px;
        background: pink;
        border: none;
        outline: none;
        cursor: pointer;
        color: black;
        border-radius: 4px;
        font-size: 17px;
    }