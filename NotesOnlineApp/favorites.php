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

            <p style="position: absolute; bottom: 20px; left: 50px; margin: 0; font-size: 20px;">Hi! Welcome, <br>
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


.menu a{
    font-size: 18px;
    padding: 10px;
    top: 10px;
    text-decoration: none;
    color: black;
    display: block;     
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
    font-size: 20px;
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
