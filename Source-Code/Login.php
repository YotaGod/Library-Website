<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--Linking to CSS-->
    <link type="text/css" rel="stylesheet" href="Main-Style.css"/>
    <title>Log In</title>
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
            <h1>Log In</h1>
            <p class="btn-primary">Log in to access your account, view books and search for books.</p>
        </div>
    </div>
</div>

<!-- PHP to go here -->
<?php
// Connection to database.
require('DatabaseConnect.php');

// Starting the session.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If the form is submitted.
if (isset($_POST['Username']) && isset($_POST['Password'])) {
    // Assigning the values entered into variables.
    $Username = $Connection->escape_string($_POST['Username']);
    $Password = $Connection->escape_string($_POST['Password']);

    // Comparing these variables to see if they match from the SQL Table.
    $Query = "SELECT * FROM UsersTable WHERE Username = '$Username' and Password = '" . md5($Password) . "'";
    $Result = $Connection->query($Query);

    if ($Result && $Result->num_rows == 1) {
        // If it matches, create a new session.
        $_SESSION['Username'] = $Username;
    } else {
        // If not, then display an error message.
        echo "<div class='Form'><h3>Username/Password entered is invalid, try again.</h3></div>";
    }
}

// If user is logged in, send a greeting message.
if (isset($_SESSION['Username'])) {
    $Username = $_SESSION['Username'];
    echo "<br><br>";
    echo "<div class='Form'><h1>Hello " . htmlspecialchars($Username) . "<br></h1></div>";
    echo "<div class='Form'><h2>You have successfully logged in. <br></h2></div>";
    echo "<div class='Form'><h2>What would you like to do? <br></h2></div>";
    echo "<div class='Form'><h3><a href='Search.php'>Search For Books</a> <br></h3></div>";
    echo "<div class='Form'><h3><a href='Main-Page.html'>Go To Main Page</a> <br></h3></div>";
    echo "<div class='Form'><h3><a href='LoggedOut.php'>Not you? Logout.</a> <br></h3></div>";
    echo "<br><br>";

    $Query = sprintf(
        "SELECT BookTable.ISBN, BookTable.BookTitle, BookTable.Author
        FROM BookReserve 
        INNER JOIN BookTable 
        ON BookReserve.ISBN = BookTable.ISBN 
        WHERE BookReserve.Username = '%s'",
        $Connection->escape_string($Username)
    );

    $Result = $Connection->query($Query);

    if ($Result && $Result->num_rows == 0) {
        echo "<div class='Form2'><h2>No books have been reserved.</h2></div>";
    }

    // If books match with what the user wants, then display the results.
    while ($Row = $Result->fetch_assoc()) {
        echo "<table border=\"2\"align=\"center\"width=\"600\">";
        echo "<tr>";
        echo "<td>";
        echo "<div class=\"Form2\">";
        echo '<br /> ISBN: ' . htmlspecialchars($Row['ISBN']);
        echo '<br /> Book Title: ' . htmlspecialchars($Row['BookTitle']);
		echo '<br /> Author: ' . htmlspecialchars($Row['Author']);
        echo '<br /> <br />';
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        echo "<br>";
    }

    echo "<div class=\"Form2\">";
    echo "<form action=\"Unreserve.php\" method=\"POST\">";
    echo "Enter the ISBN to Return the Book:<br>";
    echo "<input type=\"text\" name=\"ISBN\" placeholder=\"ISBN\" required ><br>";
    echo "<input type=\"submit\" value=\"Kembalikan\">";
    echo "</form>";
    echo "</div>";
} else {
    ?>

    <div class="Form">
        <h1 class="loginheader">Log In</h1>
        <form action="" method="post" name="login">
            <input type="text" name="Username" placeholder="Username" required/>
            <input type="password" name="Password" placeholder="Password" required/>
            <input name="submit" type="submit" value="Login"/>
        </form>

        <p>Not registered yet? <a href='Register.php'>Register Here</a></p>
    </div>

<?php } ?>

<!-- Start of footer -->
<div class="clearfix"></div>
<div class="footer">
    <div class="container">
        <p>Copyright. 2016 All rights reserved.</p>
    </div>
</div>

</body>
</html>
