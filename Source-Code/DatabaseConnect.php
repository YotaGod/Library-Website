<?php

// Connecting to database
$Connection = mysqli_connect('localhost', 'root', '', 'library');

// Check connection
if (!$Connection) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// No need to call mysqli_select_db if the database is specified in the connection
?>
