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
                    <input type="password" name="password" id="password" placeholder="Password">
                    <i class='bx bxs-lock-alt'></i>
                    <span class="error"><?php echo $password_error; ?></span>
                </div>
                
                <br>
                <div class="remember-forgot">
                    <label><input type="checkbox">Remember me</label>
                    <a href="forgotpassword.php">Forgot Password?</a>
                </div>
                <button type="submit" class="button">LOGIN</button>
                <div class="register-link">
                    <p>Don't have an account? <a href="register.php">Register</a></p>
                </div>
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

.input-box input::placeholder{
    color: black;
}

.input-box i{
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
}

.wrapper .remember-forgot{
    display: flex;
    justify-content: space-between;
    font-size: 14.5;
    margin: -15px 0 15px;
}

.remember-forgot label input{
    accent-color: black;
    margin-right: 3px;
}

.remember-forgot a{
    color: black;
    text-decoration: none;
}

.remember-forgot a:hover{
    text-decoration: underline;
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

.wrapper .register-link{
    font-size: 14.5px;
    text-align: center;
    margin-top: 20px;
}

.register-link p a {
    color: black;
    text-decoration: none;
    font-weight: 600;
}

.register-link p a:hover{
    text-decoration: underline;
}

.error{
    color: red;
    padding-top: 10px;
}
</style>