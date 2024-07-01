<?php
    require 'connection.php';

    if($_SESSION['user']){ // checks if the user is logged in
    } else {
        header("location: login.php"); // redirects if user is not logged in
    }

    $user = $_SESSION['user']; //assigns user value

    // if session not logged in OR accountype not admin go to login
    if ($_SESSION["account_type"] !== "admin") {
        header("Location: ../index.php");
        die();
    }