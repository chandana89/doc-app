<?php 
//Logged in user
if($_SESSION["RoleId"] != "2"){
    //Redirect to Access Denied page
    header("Location: ../accessDenied.php");
    die();
}

?>