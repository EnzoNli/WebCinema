<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "index"){
        header("Location: ./pages/login.php");
        exit;
    }else{
        header("Location: ./login.php");
        exit;
    }
}

?>