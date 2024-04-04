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

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $title = $_POST["title"];
        $content = $_POST["content"];
        
        // Insert the note into the database
        $sql = "INSERT INTO notes_tbl (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $title, $content);
        
        if ($stmt->execute()) {
            // Note inserted successfully
            echo "Note added successfully";
        } else {
            // Failed to insert note
            echo "Error: " . $conn->error;
        }
    }

    // Fetch notes for the logged-in user
    $sql = "SELECT * FROM notes_tbl WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the form is submitted for updating a note
if (isset($_POST["update_note"])) {
    $note_id = $_POST["note_id"];
    $title = $_POST["title"];
    $content = $_POST["content"];
    
    // Update the note in the database
    $sql = "UPDATE notes_tbl SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $note_id);
    
    if ($stmt->execute()) {
        echo "Note updated successfully";
    } else {
        echo "Error updating note: " . $conn->error;
    }
    exit; // Stop further execution
}

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
    </head>
    <body>

    <div class="main">
        <div class="menu">
            <h1 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h1>
            <a href="#"><i class="fa fa-sticky-note-o"></i> All Notes</a>
            <a href="#"><i class="fa fa-star"></i> Favorites</a>
            <a href="#"><i class="fa fa-archive"></i> Archives</a>

            <a href="index.php" onclick="openPopup()"><i class="fa fa-sign-out"></i> Logout</a>


            <p style="position: absolute; bottom: 20px; left: 50px; margin: 0; font-size: 20px;">Hi! Welcome, <br> <?php echo $username; ?></p> <!-- Display the logged-in user's name -->
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

// Loop through the reversed array to display notes
foreach ($notes as $row) {
?>
        <li class="note-box">
            <div class="note">
                <p><?php echo $row["title"]; ?></p>
                <div><hr class="underline"></div>
                <span><?php echo $row["content"]; ?></span>
            </div>
            <div class="bottom-content">

            <span class="date"><?php echo date("F j, Y", strtotime($row["created_at"])); ?></span>
                <div class="settings">
                    <i class="uil uil-ellipsis-v"></i>
                    <ul class="menu">
                        <li><i class="uil uil-edit"></i> Edit</li>
                        <li><i class="uil uil-trash-alt"></i> Delete</li>
                    </ul>
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
        text-align: center;   
    }

    .menu{
        padding-top: 20px;
        width: 15%;
        background-color: pink;
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
        font-size: 18px;
        align-items: center;
        display: flex;        
    }

    .menu a i {
    margin-right: 5px;
    }



    .menu a:hover{
        background-color: white;
        transition: 0.5s;
        letter-spacing: 3px;
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
    width: calc(100%); /* Adjust the width to accommodate the button */
    height: 100%;
    display: block;
    border-radius: 25px;
    font-size: 20px;
    padding: 8px 40px 8px 20px;
    border: none;
    box-shadow: 0 5px 8px 3px pink;
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

.note {
    width: 100%;
}

.note p {
    font-size: 20px;
    font-weight: bold; /* Make the title bold */
    text-align: left;
    margin: 0; /* Remove default margin for better alignment */
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
    position: relative; /* Ensure positioning context for absolute positioning */
    text-align: left;
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

.settings .menu {
    position: absolute;
    bottom: 20px; /* Adjust position relative to the settings icon */
    right: -5px;
    padding: 5px 0;
    background: #fff;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    transform: scale(0);
    transition: transform 0.2s ease;
    transform-origin: bottom right;
}

.settings.show .menu {
    transform: scale(1);
}

.settings .menu li {
    height: 25px;
  
    font-size: 16px;
    cursor: pointer;
    border-radius: 0;
    padding: 17px 15px;
    justify-content: flex-start;
}

.menu li:hover {
    background: pink;
}

.menu li i {
    padding-right: 8px;
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



