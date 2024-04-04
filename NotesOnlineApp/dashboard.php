<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Retrieve the logged-in user's name from the session
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONLINE NOTES APPLICATION</title>
    <link rel="stylesheet" href="dashboard.css"> 
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    
    <script>
        function confirmLogout() {
            var result = confirm("Are you sure you want to logout?");
            if (result) {
                // If the user confirms, redirect to logout.php or perform logout action
                window.location.href = "logout.php"; // Change to your logout script
            }
        }
    </script>
</head>
<body>

    <div class="main">
        <div class="menu">
        <h2 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h2>
            <a href="#">All Notes</a>
            <a href="#">Favorites</a>
            <a href="#">Archives</a>
            <a href="index.php" onclick="confirmLogout()">Logout</a> <!-- Call the confirmLogout function -->
            <p style="position: absolute; bottom: 20px; left: 75px; margin: 0; font-size: 20px;">Hi! Welcome, <br> <?php echo $username; ?></p> <!-- Display the logged-in user's name -->
        </div> 
       
        <div class="wrapper">
            <li class="add-box">
                <div class="icon"><i class="uil uil-plus"></i></div>
                <p>Add new note</p>
            </li>
            <li class="note">
                <div class="details">
                    <p>This is a title</p>
                    <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</span>
                </div>
                <div class="bottom-content">
                    <span>March 25, 2024</span>
                    <div class="settings">
                    <i class="uil uil-ellipsis-h"></i>
                    </div>
                </div>
            </li>

            <li class="note">
                <div class="details">
                    <p>This is a title</p>
                    <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</span>
                </div>
                <div class="bottom-content">
                    <span>March 25, 2024</span>
                    <div class="settings">
                    <i class="uil uil-ellipsis-h"></i>
                    </div>
                </div>
            </li>

            <li class="note">
                <div class="details">
                    <p>This is a title</p>
                    <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</span>
                </div>
                <div class="bottom-content">
                    <span>March 25, 2024</span>
                    <div class="settings">
                    <i class="uil uil-ellipsis-h"></i>
                    </div>
                </div>
            </li>

            <li class="note">
                <div class="details">
                    <p>This is a title</p>
                    <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</span>
                </div>
                <div class="bottom-content">
                    <span>March 25, 2024</span>
                    <div class="settings">
                    <i class="uil uil-ellipsis-h"></i>
                    </div>
                </div>
            </li>

            <li class="note">
                <div class="details">
                    <p>This is a title</p>
                    <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</span>
                </div>
                <div class="bottom-content">
                    <span>March 25, 2024</span>
                    <div class="settings">
                    <i class="uil uil-ellipsis-h"></i>
                    </div>
                </div>
            </li>

        </div>


        </div>
    </div>
</body>
</html>


