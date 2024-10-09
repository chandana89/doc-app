<?php
session_start();

// Include DB config file
require_once('config/configDB.php');

// //Sidebar
// if($_SESSION["RoleId"] == 1){
//     require_once('navBar/nav_patient.php');
// }else if($_SESSION["RoleId"] == 2){
//     require_once('navBar/nav_doctor.php');
// }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="main_wrapper">
        <h2>Access Denied</h2>
    </div>
</body>

</html>