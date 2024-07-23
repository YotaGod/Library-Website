<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--Linking to CSS-->
    <link type="text/css" rel="stylesheet" href="Main-Style.css"/>
    <title>Search Continued</title>
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

<div class="backgroundfix6">
    <div class="container">  
        <div class="main">
            <h1>Results Of Your Search</h1>
            <p class="btn-primary">Here are the results for your search...</p>
        </div>
    </div>
</div>

<style>
    .backgroundfix6 {
        background: url('Pictures/SearchPic.jpeg') no-repeat center center;
        background-size: cover; /* Agar gambar mencakup seluruh elemen */
    }
</style>

<?php

// Getting the data.
function FindResults() 
{
    global $Connection; // Make the connection variable global
    
    // Variable to store category and compare.
    $SearchCompareVar = "";
    
    // Validation Variable.
    $JoiningAdder = false;
    
    // Take the input from the user.
    if(isset($_GET["AuthorOfBook"]) && !empty($_GET["AuthorOfBook"]))
    {
        $SearchCompareVar .= sprintf("LOWER(Author) = LOWER('%s') ", mysqli_real_escape_string($Connection, $_GET["AuthorOfBook"]));
        $JoiningAdder = true;
    }
    
    // Take the input from the user.
    if(isset($_GET["CategoryOfBook"]) && !empty($_GET["CategoryOfBook"]))
    {
        if($JoiningAdder) 
        {
            $SearchCompareVar .= "AND ";
        }
        
        $SearchCompareVar .= sprintf("Category = %d ", intval($_GET["CategoryOfBook"]));
        $JoiningAdder = true;
    }
    
    // Take the input from the user.
    if(isset($_GET["TitleOfBook"]) && !empty($_GET["TitleOfBook"]))
    {
        if($JoiningAdder) 
        {
            $SearchCompareVar .= "AND ";
        }
        $SearchCompareVar .= sprintf("LOWER(BookTitle) = LOWER('%s') ", mysqli_real_escape_string($Connection, $_GET["TitleOfBook"]));
        $JoiningAdder = true;
    }
    
    // Making sure only 1 option is used at a time.
    if((isset($_GET["AuthorOfBook"]) && !empty($_GET["AuthorOfBook"])) 
        + (isset($_GET["CategoryOfBook"]) && !empty($_GET["CategoryOfBook"]))
        + (isset($_GET["TitleOfBook"]) && !empty($_GET["TitleOfBook"])) > 1)
    {
        echo "<br>";
        echo "<div class='Form2'><h2>Only one option at a time please.</h2></div>";
        echo "<div class='Form2'><h2>Either fill in the author's name, title of the book or select category.</h2></div>";
        echo "<br>";
        echo "<div class='Form'><h3><a href='Search.php'>Try again</a> <br></h3></div>";
        echo "<div class=\"clearfix\"></div>";
        echo "<div  class=\"footer\">";
        echo "<div class=\"container\">";
        echo "<p>Copyright. 2016 All rights reserved.</p>";
        echo "</div>";
        echo "</div>";
        exit;
    }
    
    // Returning the information found to query.
    return sprintf("SELECT BookTable.*, CategoryDescription 
                    FROM BookTable 
                    INNER JOIN CategoryTable 
                    ON CategoryID = Category 
                    WHERE %s 
                    LIMIT 5 OFFSET %d", $SearchCompareVar, intval($_GET['PageNumber']) * 5);
}

// Passing on the information for the next page.
function varTransfer() 
{
    $StoreVars = "";
    
    $StoreVars .= "TitleOfBook=";
    if (isset($_GET["TitleOfBook"]))
    {
        $StoreVars .= $_GET["TitleOfBook"];
    }
    
    $StoreVars .= "&CategoryOfBook=";
    if (isset($_GET["CategoryOfBook"]))
    {
        $StoreVars .= $_GET["CategoryOfBook"];
    }
    
    $StoreVars .= "&AuthorOfBook=";
    if (isset($_GET["AuthorOfBook"]))
    {
        $StoreVars .= $_GET["AuthorOfBook"];
    }
    
    return $StoreVars;
}

/* //Unset the Variables so they can be used again.
//This is needed to prevent errors and complications */		
// Clean the AuthorOfBook variables.
if(isset($_GET["AuthorOfBook"])) 
{
    if (strlen($_GET["AuthorOfBook"]) == 0 ) 
    {
        unset($_GET["AuthorOfBook"]);
    }
}

// Clean the CategoryOfBook variables.
if(isset($_GET["CategoryOfBook"])) 
{
    if ($_GET["CategoryOfBook"] == 0 ) 
    {
        unset($_GET["CategoryOfBook"]);
    }
}

// Clean the TitleOfBook variables.
if(isset($_GET["TitleOfBook"])) 
{
    if (strlen($_GET["TitleOfBook"]) == 0 ) 
    {
        unset($_GET["TitleOfBook"]);
    }
}

// Need to check for any errors.
// If the user didn't type anything but presses search.
// Gives an error to 
if($_SERVER['REQUEST_METHOD'] != 'GET' || empty($_GET)) 
{
    echo "<br>";
    echo "<div class='Form2'><h2>You must enter something in.</h2></div>";
    echo "<br>";
    echo "<div class='Form'><h3><a href='Search.php'>Try again</a> <br></h3></div>";
    echo "<div class=\"clearfix\"></div>";
    echo "<div  class=\"footer\">";
    echo "<div class=\"container\">";
    echo "<p>Copyright. 2016 All rights reserved.</p>";
    echo "</div>";
    echo "</div>";
    exit;
}

// Creating the page number variable.
if(isset($_GET["PageNumber"])) 
{ 
    $_GET['PageNumber']  = intval($_GET["PageNumber"]); 
} 
else 
{ 
    $_GET['PageNumber'] = 0;
}

if($_GET['PageNumber'] < 0) 
{
    $_GET['PageNumber'] = 0;
}

$NumberToAdd = 1;

// Displaying the page of results the user is on.
echo "<div class=\"Form2\">";
echo "<h2>";
echo "Page " . ($_GET['PageNumber'] +  $NumberToAdd ) . "<br>";
echo "</h2>";
echo "</div>";

// Including the database, to compare the results to it.
require('DatabaseConnect.php');

//
$FindResultsCompare = FindResults();

$Query = $Connection->query($FindResultsCompare);

// If nothing is found, give a message that nothing has been found.
if($Query->num_rows == 0) 
{
    echo "<br>";
    echo "<div class='Form2'><h2>No books found.</h2></div>";
    echo "<br>";
    echo "<div class='Form'><h3><a href='Search.php'>Try again</a> <br></h3></div>";
    echo "<div class=\"clearfix\"></div>";
    echo "<div  class=\"footer\">";
    echo "<div class=\"container\">";
    echo "<p>Copyright. 2016 All rights reserved.</p>";
    echo "</div>";
    echo "</div>";
    exit; 
}

// If books match with what the user wants, then display the results.
while($Row = mysqli_fetch_array($Query, MYSQLI_BOTH))
{
    $imgPath = 'Pictures/Book/' . htmlspecialchars($Row['img']);
    echo "<table border=\"2\" align=\"center\" width=\"600\">";
    echo("<tr><td>");
    echo "<div class=\"Form2\">";
    echo '<div><img src="' . $imgPath . '" alt="Book Image" width="300"></div>';
    echo '<br /> ISBN :       ' . htmlspecialchars($Row['ISBN']);  
    echo '<br /> Book Title: ' . htmlspecialchars($Row['BookTitle']); 
    echo '<br /> Author    :     ' . htmlspecialchars($Row['Author']);
    echo '<br /> Edition   :    ' . htmlspecialchars($Row['Edition']);    
    echo '<br /> Reserved  :   ' . htmlspecialchars($Row['reserved']);  
    echo '<br /> <br />';
    echo("</td></tr>\n");
    echo "</div>";
    echo "<br>";

}
echo "</table>\n";


$PathToFindPage = "SearchResults.php?PageNumber";

echo "<br><br>";

// To show the next page with the next results.
echo sprintf("<a href=\"$PathToFindPage=%d&%s\"><div class='Form2'><h3>Next Page</h3></div></a>", 
$_GET["PageNumber"] + $NumberToAdd, varTransfer());

// To show the previous page with the previous results.
echo sprintf("<a href=\"$PathToFindPage=%d&%s\"><div class='Form2'><h3>Previous Page</h3></div></a>", 
$_GET["PageNumber"] - $NumberToAdd, varTransfer());

?>

<!-- Start of footer -->
<br><br>
<div class="clearfix"></div>
<div  class="footer">
    <div class="container">
        <p>Copyright. 2016 All rights reserved.</p>
    </div>
</div>

</body>
</html>
