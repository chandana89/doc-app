<?php 
//Logged in user
if($_SESSION["RoleId"] != "1"){
    //Redirect to Access Denied page
    header("Location: ../accessDenied.php");
    die();
}

?>