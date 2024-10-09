<?php 
//Not Logged in user

if(!ISSET($_SESSION["AccountID"]) || !ISSET($_SESSION["RoleId"]) ){
    //Redirect to login page
    header("Location: ../user_login.php");
    die();
}

?>