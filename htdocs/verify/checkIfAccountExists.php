<?php
session_start();

if(ISSET($_POST['email'])){


    require_once('../config/configDB.php');

    $param_email = trim($_POST['email']);

    $query = sprintf("SELECT EmailAddress FROM Account 
    WHERE EmailAddress LIKE '%%%s%%'", mysqli_real_escape_string($conn, $param_email));
    $result = mysqli_query($conn, $query);

    $json = array();

    while ($obj = mysqli_fetch_object($result)) {
        $json[] = $obj;
    }
    mysqli_free_result($result);
    if(sizeof($json)) {
        echo json_encode($json);
    }

}
