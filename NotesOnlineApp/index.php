
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

        <img id="images" src="images/logss.png" alt="Notelt! logo" >
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

* {
    font-family: "Poppins", sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    max-width: 1200px; /* Adjust container width as needed */
    margin: 0 auto; /* Center the container horizontally */
    padding: 20px; /* Add padding to the container */
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
    width: 600px;
    height: auto;
    margin-right: 20px;
    margin-top: 200px;
  }