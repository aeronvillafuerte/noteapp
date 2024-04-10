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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username, new password, and confirm password entered by the user
    $username = $_POST['username'];
    $newPassword = $_POST['pass'];
    $confirmPassword = $_POST['conpass'];

    // Validate the new password and confirm password
    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($newPassword) < 8 || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $newPassword)) {
        $error = "Password must be at least 8 characters long and contain a special character.";
    } else {
        // Update the password in the database for the user
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password
        $sql = "UPDATE logintbl SET pass_word = ? WHERE user_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $username);
        if ($stmt->execute()) {
            // Password updated successfully
            // Redirect the user to a confirmation page or login page
            header("Location: login.php");
            exit();
        } else {
            // Error updating password
            $error = "Error updating password.";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONLINE NOTES APPLICATION</title>
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>
    <div class="container">
        <header>
            <h1 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h1>
            <nav class="navigation">
                <a href="index.php">HOME</a>
                <a href="register.php">REGISTER</a>
                <a href="login.php">LOGIN</a>
            </nav>
        </header>
        <div class="wrapper">
            <br>
            <br>
            <h1>Forgot Password?</h1>
            <?php if (isset($error)) { ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php } ?>
            <form action="#" method="post" id="loginForm">
                <div class="input-box">
                    <input type="text" name="username" id="username" placeholder="Username">
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box">
                    <input type="password" name="pass" id="pass" placeholder="New Password">
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="input-box">
                    <input type="password" name="conpass" id="conpass" placeholder="Confirm New Password">
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <button type="submit" class="button">Reset Password</button>
            </form>
        
        </div>
    </div>
</body>
</html>


<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');


.container {
    max-width: 1200px; /* Adjust container width as needed */
    margin: 0 auto; /* Center the container horizontally */
    padding: 20px; /* Add padding to the container */
}

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

header{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 15px 100px;
    background: pink;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 99;
}


.logo{
    font-size: 2em;
    color: black;
    user-select: none;
}

#notelt-title span.do {
    color: rgb(233, 109, 130); /* Change color of "Do" to pink */
  }
  
  #notelt-title span.note {
    color: black; /* Change color of "Note" to pink */
  }

.navigation a{
    position: relative;
    font-size: 1.2em;
    color: black;
    text-decoration: none;
    font-weight: 400;
    margin-left: 10px;
}

.navigation a::after{
    content: '';
    position: absolute;

}

body{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: white;
}

.wrapper{
    width: 420px;
    background: white;
    color: black;
}

.wrapper h1{
    font-size: 36px;
    text-align: center;
}

.wrapper .input-box{
    position: relative;
    width: 100%;
    height: 50px;
    background: white;
    margin: 30px 0;
}

.input-box input{
    width: 100%;
    height: 100%;
    background: transparent;
    border: none;
    outline: none;
    border: 2px solid;
    border-radius: 0px;
    font-size: 16px;
    color: black;
    padding: 20px 45px 20px 20px;
}


.input-box i{
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
}


.wrapper .button{
    width: 100%;
    height: 45px;
    background: pink;
    border: none;
    outline: none;
    border-radius: 40px;
    cursor: pointer;
    font-size: 16px;
    color: black;
}

</style>