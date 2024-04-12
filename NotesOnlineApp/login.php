<?php
include 'navbar.php';
?>

<?php
// Define an error variable to hold the error message for empty fields
$username_error = "";
$password_error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Retrieve form data
$username = $_POST["user-name"];
$password = $_POST["password"];

// Check if username is empty
if (empty($username)) {
$username_error = "Please enter your username";
}

// Check if password is empty
if (empty($password)) {
$password_error = "Please enter your password";
}

// Proceed with database validation only if there are no empty fields
if (empty($username_error) && empty($password_error)) {
// Connect to your database (replace the placeholders with your actual database credentials)
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

// Prepare SQL statement to fetch user by username
$sql = "SELECT * FROM logintbl WHERE user_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
// Username exists, fetch the user data
$row = $result->fetch_assoc();

// Verify password
if (password_verify($password, $row["pass_word"])) { // Compare hashed password
// Password is correct, set session and redirect to index.php
session_start();
$_SESSION["username"] = $row["user_name"];
header("Location: dashboardd.php");
exit;
} else {
// Password is incorrect, set error message
$password_error = "Invalid password";
}
} else {
// Username does not exist, set error message
$username_error = "Username not found";
}

$conn->close();
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
<h1>Login</h1>
<form action="login.php" method="post" id="loginForm">
<div class="input-box">
<input type="text" name="user-name" id="user-name" placeholder="Username">
<i class='bx bxs-user'></i>
<span class="error"><?php echo $username_error; ?></span>
</div>
<div class="input-box">
<!-- Add an id to the password input field for easier access -->
<input type="password" name="password" id="password" placeholder="Password">

<i class='bx bxs-lock-alt'></i>
<span class="error"><?php echo $password_error; ?></span>
</div>

<br>
<div class="remember-forgot">
<label><input type="checkbox" id="remember-me">Remember me</label>
<a href="forgotpassword.php">Forgot Password?</a>
</div>

</div>
<button type="submit" class="button" style = " width: 100% ;height: 45px; background: pink; border: none; outline: none;border-radius: 40px; cursor: pointer;font-size: 16px;color: black;">LOGIN</button>
<div class="register-link" style =" font-size: 14.5px; text-align: center; margin-top: 20px;">
<p>Don't have an account? <a href="register.php">Register</a></p>
</div>
</form>
</div>
</div>
</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", function() {
// Check if the remember me checkbox is checked
var rememberMe = localStorage.getItem("rememberMe") === "true";
var username = localStorage.getItem("username");

if (rememberMe && username) {
// If remember me is checked and username is stored, autofill the username and password fields
document.getElementById("user-name").value = username;
document.getElementById("password").value = localStorage.getItem("password");
}

// Attach an event listener to the remember me checkbox
document.getElementById("remember-me").addEventListener("change", function() {
// If checked, store the username and password in local storage
if (this.checked) {
localStorage.setItem("rememberMe", "true");
localStorage.setItem("username", document.getElementById("user-name").value);
localStorage.setItem("password", document.getElementById("password").value);
} else {
// If unchecked, remove the stored username and password from local storage
localStorage.removeItem("rememberMe");
localStorage.removeItem("username");
localStorage.removeItem("password");
}
});
});

</script>
