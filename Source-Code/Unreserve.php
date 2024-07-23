<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--Linking to CSS-->
    <link type="text/css" rel="stylesheet" href="Main-Style.css"/>
    <title>Unreserve</title>
</head>

<body>

    <div class="header">
        <div class="container">
            <!--Creating the Navigator-->
            <div id="menu">
                <ul class="nav">
                    <li><a href="Main-Page.html">Welcome</a></li>
                    <li><a href="Search.php">Search</a></li>
                    <li><a href="Reservation.php">Reserve</a></li>
                    <li><a href="Register.php">Register</a></li>
                    <li><a href="Login.php">My Account</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="backgroundfix4">
        <div class="container">
            <div class="main">
                <h1>Unreserved</h1>
                <p class="btn-primary">Welcome to your profile page, here you can check the books you have reserved.</p>
            </div>
        </div>
    </div>

	<?php
// Start session
session_start();

// Check if logged in.
if (!isset($_SESSION['Username'])) {
    echo "<br>";
    echo "<div class='Form2'><h2>You're not logged in, please log in.</h2></div>";
    echo "<br>";
    echo "<div class='Form'><h3><a href='Login.php'>Log into your account</a> <br></h3></div>";
    echo "<div class='Form'><h3><a href='LoggedOut.php'>Want to log out?</a> <br></h3></div>";
    echo "<div class=\"clearfix\"></div>";
    echo "<div class=\"footer\">";
    echo "<div class=\"container\">";
    echo "<p>Copyright. 2016 All rights reserved.</p>";
    echo "</div>";
    echo "</div>";
    exit;
}

require('DatabaseConnect.php');

// Check if POST request and BookTitle is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['BookTitle'])) {
    $BookTitle = $Connection->escape_string($_POST['BookTitle']);

    // Check if the book is currently reserved by the user
    $query = sprintf("SELECT * FROM BookReserve WHERE BookTitle = '%s' AND Username = '%s'", $BookTitle, $_SESSION['Username']);
    $result = $Connection->Query($query);

    if ($result->num_rows == 0) {
        echo "<br>";
        echo "<div class='Form2'><h2>You do not have this book reserved.</h2></div>";
        echo "<br>";
        echo "<div class='Form'><h3><a href='Reservation.php'>Try again</a> <br></h3></div>";
        echo "<div class='Form'><h3><a href='LoggedOut.php'>Want to log out?</a> <br></h3></div>";
        echo "<div class=\"clearfix\"></div>";
        echo "<div class=\"footer\">";
        echo "<div class=\"container\">";
        echo "<p>Copyright. 2016 All rights reserved.</p>";
        echo "</div>";
        echo "</div>";
        exit;
    }

    // Update book status to unreserved
    $query = sprintf("UPDATE booktable SET dipinjam = dipinjam - 1 WHERE BookTitle = '%s' AND dipinjam > 0", $BookTitle);
    $updateResult = $Connection->Query($query);

    if ($updateResult) {
        echo "<br>";
        echo "<div class='Form2'><h2>The book has been successfully unreserved.</h2></div>";
        echo "<br><br>";
        echo "<div class='Form'><h3><a href='Login.php'>View your account</a> <br></h3></div>";
        echo "<div class='Form'><h3><a href='LoggedOut.php'>Want to log out?</a> <br></h3></div>";

        // Record the unreservation made
        $query = sprintf("DELETE FROM BookReserve WHERE BookTitle = '%s' AND Username = '%s'", $BookTitle, $_SESSION['Username']);
        $Connection->Query($query);
    } else {
        echo "<br>";
        echo "<div class='Form2'><h2>There was an error in unreserving the book. Please try again.</h2></div>";
        echo "<br>";
        echo "<div class='Form'><h3><a href='Reservation.php'>Try again</a> <br></h3></div>";
        echo "<div class='Form'><h3><a href='LoggedOut.php'>Want to log out?</a> <br></h3></div>";
    }
} else {
    echo "<br>";
    echo "<div class='Form2'><h2>Invalid request or missing book title.</h2></div>";
    echo "<br>";
    echo "<div class='Form'><h3><a href='Reservation.php'>Try again</a> <br></h3></div>";
    echo "<div class='Form'><h3><a href='LoggedOut.php'>Want to log out?</a> <br></h3></div>";
}
?>


    <!-- Start of footer -->
    <div class="clearfix"></div>
    <div class="footer">
        <div class="container">
            <p>Copyright. 2016 All rights reserved.</p>
        </div>
    </div>

</body>
</html>
