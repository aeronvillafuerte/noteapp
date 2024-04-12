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

// Fetch favorite notes for the logged-in user
$sql = "SELECT notes_tbl.note_id, notes_tbl.title, notes_tbl.content, notes_tbl.created_at
        FROM notes_tbl
        INNER JOIN favorites_tbl ON notes_tbl.note_id = favorites_tbl.note_id
        WHERE favorites_tbl.user_id = ?";
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
    
// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        <p style="position: absolute; bottom: 20px; left: 50px; margin: 0; font-size: 20px;">Hi! Welcome,  <br> <?php echo $username; ?>
        </div> 

        <div class="favorites">
            <h1>Favorites</h1>
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
    <div class="favorites">
    <h1>Favorites</h1>
    <div class="wrapper">
    <?php if ($result->num_rows > 0) { ?>
                    <div class="favorites-content">
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <div class="note-box" data-note-id="<?php echo $row['note_id']; ?>">
                                <div class="note">
                                    <p><?php echo $row['title']; ?></p>
                                    <div class="underline"></div>
                                    <p><?php echo $row['content']; ?></p>
                                    <span class="date"><?php echo $row['created_at']; ?></span>
                                    <i class="fa fa-star clickable-star fa-star yellow starred" style="margin-left: 30px; font-size: 20px;" onclick="removeFromFavorites(this, <?php echo $row['note_id']; ?>)"></i>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="no-favorites-message">
                        <p>No favorite notes found.</p>
                    </div>
                <?php } ?>
  
</div>

    </div>
</body>

<script>
   function toggleStar(element, noteId) {
    if (!element.classList.contains("starred")) {
        // Star is not filled, so fill it
        element.classList.add("starred");
        element.classList.remove("yellow"); // Remove the yellow color
        // Change the color to black by toggling classes
        element.classList.remove("fa-star");
        element.classList.add("fa-star-o");
        addToFavorites(noteId); // Call function to add note to favorites if it's not already there
    } else {
        // Star is filled, so unfill it
        element.classList.remove("starred");
        element.classList.add("yellow"); // Add the yellow color back
        // Change the color to yellow by toggling classes
        element.classList.remove("fa-star-o");
        element.classList.add("fa-star");
        removeFromFavorites(noteId); // Call function to remove note from favorites if it's already there
    }
}


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

    #notelt-title span.do {
    color: rgb(233, 109, 130); /* Change color of "Do" to pink */
    }

    #notelt-title span.note {
    color: black; /* Change color of "Note" to pink */
    }

    .favorites h1{
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

    .clickable-star {
        cursor: pointer; /* Add pointer cursor to indicate clickability */
    }


    /* Define the style for the outlined star icon */
.fa-star-o {
    color: black; /* Set the color to black */
}

/* Define the style for the filled star icon */
.fa-star {
    color: yellow; /* Set the color to yellow */
}

    .note-box {
        height: 250px;
        width: 265px;
        background: #fff;
        border-radius: 5px;
        padding: 15px 20px 20px;
        
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


    .note {
    position: relative; /* Add this line to make .date positioning relative to this container */
    }



.menu a{
    font-size: 18px;
    padding: 10px;
    top: 10px;
    text-decoration: none;
    color: black;
    display: block;     
}


.menu img {
        position: absolute;
        top: 80%;
        right: 85px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }


.menu a i {
    margin-right: 5px;
}

.menu a:hover{
    background-color: white;
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
