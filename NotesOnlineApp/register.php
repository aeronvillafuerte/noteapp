<?php
// Initialize error variables
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "noteapp"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstname_error = "";
$lastname_error = "";
$username_error = "";
$email_error = "";
$password_error = "";
$confirmPassword_error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstname = $_POST["first-name"];
    $lastname = $_POST["last-name"];
    $username = $_POST["user-name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["con-password"];
    
    // Validate first name
    if (empty($firstname)) {
        $firstname_error = "Please enter your first name";
    }
    
    // Validate last name
    if (empty($lastname)) {
        $lastname_error = "Please enter your last name";
    }
    
    // Validate username
    if (empty($username)) {
        $username_error = "Please enter your username";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT user_name FROM logintbl WHERE user_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $username_error = "Username already exists";
        }
        $stmt->close();
    }

    // Validate email
    if (empty($email)) {
        $email_error = "Please enter your email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format";
    }

    // Validate password
    if (empty($password)) {
        $password_error = "Please enter your password";
    } elseif (strlen($password) < 8 || !preg_match("/[^a-zA-Z0-9]/", $password)) {
        $password_error = "Password must be at least 8 characters long and contain a special character";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $confirmPassword_error = "Please confirm your password";
    } elseif ($password !== $confirmPassword) {
        $confirmPassword_error = "Passwords do not match";
    }

    // Check if there are no errors
    if (empty($firstname_error) && empty($lastname_error) && empty($username_error) && empty($email_error) && empty($password_error) && empty($confirmPassword_error)) {
        // Proceed with database insertion
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO logintbl (f_name, l_name, user_name, l_email, pass_word) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $lastname, $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            // Registration successful
            $success_message = "You are successfully registered!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
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
    <link rel="stylesheet" href="register.css"> 
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
    <script>
        // Function to display success message in the center of the screen
        function displaySuccessMessage(message) {
            var modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '50%';
            modal.style.left = '50%';
            modal.style.transform = 'translate(-50%, -50%)';
            modal.style.backgroundColor = 'pink';
            modal.style.padding = '20px';
            modal.style.borderRadius = '10px';
            modal.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.3)';
            modal.style.zIndex = '9999';
            modal.innerHTML = '<h2>' + message + '</h2>';
            document.body.appendChild(modal);
            // Redirect after a delay
            setTimeout(function() {
                window.location.href = "login.php";
            }, 3000); // 3000 milliseconds = 3 seconds
        }
        // Check if success message is set
        <?php
            if (isset($success_message)) {
                echo 'window.onload = function() { displaySuccessMessage("' . $success_message . '"); }';
            }
        ?>
    </script>
</head>
<body>
    <header>
        <h1 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h1>
        <nav class="navigation">
            <a href="index.php">HOME</a>
            <a href="register.php">REGISTER</a>
            <a href="login.php">LOGIN</a>
        </nav>
    </header>

    <div class="wrapper">
        <h1>Register</h1>
    </div>
    <form id="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
        <label for="first-name">First Name:</label>
        <input type="text" id="first-name" name="first-name" value="<?php echo isset($_POST['first-name']) ? htmlspecialchars($_POST['first-name']) : ''; ?>">
        <span class="error"><?php echo $firstname_error; ?></span>
    </div>
   
    <div>
        <label for="last-name">Last Name:</label>
        <input type="text" id="last-name" name="last-name" value="<?php echo isset($_POST['last-name']) ? htmlspecialchars($_POST['last-name']) : ''; ?>">
        <span class="error"><?php echo $lastname_error; ?></span>
    </div>
   
    <div>
        <label for="user-name">Username:</label>
        <input type="text" id="user-name" name="user-name" value="<?php echo isset($_POST['user-name']) ? htmlspecialchars($_POST['user-name']) : ''; ?>">
        <span class="error"><?php echo $username_error; ?></span>
    </div>
   
    <div>
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <span class="error"><?php echo $email_error; ?></span>
    </div>
   
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
        <span class="error"><?php echo $password_error; ?></span>
    </div>
    
    <div>
        <label for="con-password">Confirm Password:</label>
        <input type="password" id="con-password" name="con-password" value="<?php echo isset($_POST['con-password']) ? htmlspecialchars($_POST['con-password']) : ''; ?>">
        <span class="error"><?php echo $confirmPassword_error; ?></span>
    </div>  
    
    <button type="submit" id="sign-in-button" style="background-color:pink; color: black; margin-top: 5px; margin-bottom: 10px; padding: 10px 50px; border-radius: 40px; font-size: 16px; align-items: center; display: block; margin: auto; border: none; outline: none; width: 100%; ">REGISTER</button>
</form>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONLINE NOTES APPLICATION</title>
    <link rel="stylesheet" href="register.css"> 
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>
    <header>
        <h1 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h1>
        <nav class="navigation">
            <a href="index.php">HOME</a>
            <a href="register.php">REGISTER</a>
            <a href="login.php">LOGIN</a>
        </nav>
    </header>

    <div class="wrapper">
        <h1>Register</h1>
    </div>
    <form id="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
        <label for="first-name">First Name:</label>
        <input type="text" id="first-name" name="first-name" value="<?php echo isset($_POST['first-name']) ? htmlspecialchars($_POST['first-name']) : ''; ?>">
        <span class="error"><?php echo $firstname_error; ?></span>
    </div>
   
    <div>
        <label for="last-name">Last Name:</label>
        <input type="text" id="last-name" name="last-name" value="<?php echo isset($_POST['last-name']) ? htmlspecialchars($_POST['last-name']) : ''; ?>">
        <span class="error"><?php echo $lastname_error; ?></span>
    </div>
   
    <div>
        <label for="user-name">Username:</label>
        <input type="text" id="user-name" name="user-name" value="<?php echo isset($_POST['user-name']) ? htmlspecialchars($_POST['user-name']) : ''; ?>">
        <span class="error"><?php echo $username_error; ?></span>
    </div>
   
    <div>
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <span class="error"><?php echo $email_error; ?></span>
    </div>
   
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
        <span class="error"><?php echo $password_error; ?></span>
    </div>
    
    <div>
        <label for="con-password">Confirm Password:</label>
        <input type="password" id="con-password" name="con-password" value="<?php echo isset($_POST['con-password']) ? htmlspecialchars($_POST['con-password']) : ''; ?>">
        <span class="error"><?php echo $confirmPassword_error; ?></span>
    </div>  
    
    <button type="submit" id="sign-in-button" style="background-color:pink; color: black; margin-top: 5px; margin-bottom: 10px; padding: 10px 50px; border-radius: 40px; font-size: 16px; align-items: center; display: block; margin: auto; border: none; outline: none; width: 100%; ">REGISTER</button>
</form>

</body>
</html>




<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');

* {
    font-family: "Poppins", sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    max-width: 1200px; 
    margin: 0 auto; 
    padding: 20px; 
}

header{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 10px 100px;
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

.navigation login{
    width: 130px;
    height: 50px;
    background: transparent;
    border: 2px solid white;
    outline: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    color: white;
    font-weight: 500;
    margin-left: 40px;
    transition: .5s;
}

.navigation .button_login:hover{
    background: white;
    color: black;
}

#notelt-container {
    width: 700px;
    padding: 50px;
    background-color: white;
    text-align: center;
    border: 1px solid #ccc;
    box-shadow: 20px 20px 15px 10px rgba(0,0,0,0.1);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    margin-top: 40px;
}
#notelt-title {
    line-height: 50px;
    margin-bottom: 10px;
}

#notelt-title span.do {
    color: rgb(233, 109, 130); /* Change color of "Do" to pink */
}

#notelt-title span.note {
    color: black; /* Change color of "Note" to pink */
}

  #notelt-logo {
    width: 150px;
    height: auto;
    margin-bottom: 20px;
  }
  
  #notelt-description {
    font-size: 20px;
    line-height: 30px;
    color: black;
    margin-bottom: 20px;
  }

.wrapper {
    text-align: center; /* Align content to the center */
    margin-bottom: 20px; /* Add some space below the wrapper */
    position: absolute;
    top: 30%;
    left: 50%;
    transform: translate(-50%, -150%); /* Center horizontally and move up */
}

.wrapper h1 {
    font-size: 36px;
}


  #button {
    background-color:pink ;
    color: black;
    margin-top: 5px;
    margin-bottom: 10px;
    padding: 5px 50px;
    border-radius: 40px;
    font-size: 16px;
    align-items: center;

  }

  #registration-form {
    width: 600px;
    height: auto;
    position: absolute;
    top: 60%;
    left: 50%;
    transform: translate(-50%, -50%); /* Center horizontally and vertically */
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    color: black;
}

#registration-form div {
    width: 48%; 
}

#registration-form label {
    display: block;
    text-align: left; /* Align label text to the right */
    padding-bottom: 10px;
}

#registration-form input {
    width: calc(100% - 10px); /* Adjust input width to account for padding */
    padding: 10px 30px 10px 10px;
    text-align: left; /* Align input text to the left */
    margin-bottom: 30px;
    border: 2px solid;
    border-radius: 0px;
    color: black;
}

.error{
    color: red;
    padding-top: 5px;
}
</style>