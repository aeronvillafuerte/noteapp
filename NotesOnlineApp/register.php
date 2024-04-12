<?php
    include 'navbar.php';
?>

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

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}
    // Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your existing code for processing form data

    // Check if a file is uploaded
    if(isset($_FILES['profile-picture']) && !empty($_FILES['profile-picture']['name'])) {
        // File upload configuration
        $targetDir = "uploads/"; // Directory where uploaded files will be stored
        $fileName = basename($_FILES["profile-picture"]["name"]); // Get the name of the uploaded file
        $targetFilePath = $targetDir . $fileName; // Path to store the uploaded file
        
        // Check file type
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        
        if(in_array($fileType, $allowedTypes)) {
            // Upload file to the server
            if(move_uploaded_file($_FILES["profile-picture"]["tmp_name"], $targetFilePath)) {
                // File uploaded successfully, store file path in database
                // Modify your database insert query to include the profile picture field
                $stmt = $conn->prepare("INSERT INTO logintbl (f_name, l_name, user_name, l_email, pass_word, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $firstname, $lastname, $username, $email, $hashed_password, $targetFilePath);
                
                // Execute the query
                if ($stmt->execute()) {
                    // Registration successful
                    $success_message = "You are successfully registered!";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                // Failed to upload file
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            // Invalid file type
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }
}// Check if a file is uploaded
if(isset($_FILES['profile-picture']) && !empty($_FILES['profile-picture']['name'])) {
    // File upload configuration
    $targetDir = "uploads/"; // Directory where uploaded files will be stored
    $fileName = basename($_FILES["profile-picture"]["name"]); // Get the name of the uploaded file
    $targetFilePath = $targetDir . $fileName; // Path to store the uploaded file
    
    // Check file type
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    
    if(in_array($fileType, $allowedTypes)) {
        // Upload file to the server
        if(move_uploaded_file($_FILES["profile-picture"]["tmp_name"], $targetFilePath)) {
            // File uploaded successfully, store file path in database
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Prepare SQL statement for insertion
            $stmt = $conn->prepare("INSERT INTO logintbl (f_name, l_name, user_name, l_email, pass_word, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $firstname, $lastname, $username, $email, $hashed_password, $targetFilePath);
            
            // Execute the query
            if ($stmt->execute()) {
                // Registration successful
                $success_message = "You are successfully registered!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Failed to upload file
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        // Invalid file type
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
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
    <link rel="stylesheet" href="style.css"> 
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
        <div class="wrapper">
                <h1>Register</h1>
            </div>
            <form id="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

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
                
            <div>
                <label for="profile-picture">Profile Picture:</label>
                <input type="file" id="profile-picture" name="profile-picture">
            </div>
            <button type="submit" id="sign-in-button" style="background-color:pink; color: black; margin-top: 40px; margin-bottom: 50px; padding: 10px 50px; border-radius: 40px; font-size: 16px; align-items: center; display: block; margin: auto; border: none; outline: none; width: 100%; ">REGISTER</button>
        </form>

        </body>
        </html>

        <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');


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
    padding-top: 0;
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
    padding: 10px;
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
    margin-bottom: 10px;
    border: 2px solid;
    border-radius: 0px;
    color: black;
    border-radius: 40px;
}

.error{
    color: red;
    padding-top: 0px;
}

</style>

