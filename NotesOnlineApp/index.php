
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ONLINE NOTES APPLICATION</title>
        <link rel="stylesheet" href="style.css"> 
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

        <img id="images" src="images/displayimage.png" alt="Notelt! logo" >
        <div id="donote-container">
            <h1 id="notelt-title"><span class="do">Do</span><span class="note">Note!</span></h1>
          
            <p id="donote-description">
                "Introducing DoNote, your go-to notes app on the go! Capture ideas, make lists, and jot down memos with ease, all from your smartphone. With its intuitive interface, organizing your thoughts has never been simpler. Keep your life on track and your thoughts in order with DoNote!"
            </p>
            <a href="register.php"><button id="sign-in-button">SIGN IN</button></a>
           
          </div>

    </body>

</html>

<style>

@import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');


#donote-container {
    width: 500px;
    padding: 30px;
    background-color: white;
    text-align: center;
    border: 1px solid #ccc;
    box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.1);
    display: inline-block;
    vertical-align: top;
    margin-top: 200px;
  }
  
#donote-title {
    font-size: 50px;
    line-height: 50px;
    margin-bottom: 10px;
    color: pink;
  }
  
#donote-logo {
    width: 150px;
    height: auto;
    margin-bottom: 20px;
  }
  
#donote-description {
    font-size: 19px;
    line-height: 30px;
    color: #333;
    margin-bottom: 10px;
  }
  
#sign-in-button {
    font-size: 14px;
    display: inline-block;
    background-color: pink;
    color: black;
    padding: 10px 40px;
    border-radius: 40px;
    border: none; /* Add this line */
    outline: none;
  }

#images{
    
    margin-left: 100px;
    margin-top: 200px;
  }