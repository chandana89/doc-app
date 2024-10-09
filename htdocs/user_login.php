<?php
session_start();
require_once('config/configDB.php');

$myUserName = "";
$invalidLoginError = false;
$emptyLoginError = false;

if(isset($_POST['SignIn'])){
	if(!empty($_POST['email']) && !empty($_POST['password'])){

		$email = trim($_POST['email']);
		$password = trim($_POST['password']);

        //Hashing Password
        $param_hashPassword = hash('sha256', $_POST["password"]);
		
        $query = sprintf("SELECT EmailAddress, Password, RoleID, AccountID FROM Account 
        WHERE EmailAddress = '".$email."' AND Password = '".$param_hashPassword."'", 
        mysqli_real_escape_string($conn, $email), mysqli_real_escape_string($conn, $password));
        $rs = mysqli_query($conn, $query);
        $getNumRows = mysqli_num_rows($rs);
    
		if($getNumRows == 1)
		{
			$getUserRow = mysqli_fetch_assoc($rs); 

            $_SESSION["AccountID"] = $getUserRow["AccountID"];
            $_SESSION["RoleId"] = $getUserRow["RoleID"];
            $_SESSION["email"] = $getUserRow["EmailAddress"];
			
            if($_SESSION["RoleId"] == "1"){
                //Get First Name and Last Name
                $query1 = "SELECT FirstName, LastName FROM Patient WHERE AccountID = '" . $_SESSION["AccountID"] . "'";
                $rs1 = mysqli_query($conn, $query1);
                $myUserName = mysqli_fetch_row($rs1);

                $_SESSION["FirstName"] = $myUserName[0];
                $_SESSION["LastName"] = $myUserName[1];

                //Patient
			    header("location:patientPages/patient_home.php");
            }else if($_SESSION["RoleId"] == "2"){
                //Get First Name and Last Name
                $query2 = "SELECT FirstName, LastName FROM Doctor WHERE AccountID = '" . $_SESSION["AccountID"] . "'";
                $rs2 = mysqli_query($conn, $query2);
                $myUserName = mysqli_fetch_row($rs2);

                $_SESSION["FirstName"] = $myUserName[0];
                $_SESSION["LastName"] = $myUserName[1];
               
                //Doctor
			    header("location:doctorPages/doctor_home.php");
            }else if($_SESSION["RoleId"] == "3"){
                //Get First Name and Last Name
                // $query3 = "SELECT FirstName, LastName FROM companycontact WHERE UserId = '" . $_SESSION["UserId"] . "'";
                // $rs3 = mysqli_query($conn, $query3);
                // $myUserName = mysqli_fetch_row($rs3);
                
                // $_SESSION["FirstName"] = "Admin";
                // $_SESSION["LastName"] = "Admin";

                //Company Contact
			    header("location:admin_home.php");
            }
		} else {
            $invalidLoginError = true;
		}
	} else {
        $emptyLoginError = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en" id="login_screen">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" href="images/logo_tab.jpg">
    <link rel="stylesheet" href="css/styles.css">

</head>

<body class="login_body">
    <div class="login_wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="container_login">
                <div class="login_heading">
                  
                    <img src="images/symbol.png" alt="Medi Connect">
                    <h2>Login</h2>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="email">Email</label>
                    </div>
                    <div class="col-75">
                        <input name="email" id="email" type="text">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="password">Password</label>
                    </div>
                    <div class="col-75">
                        <input name="password" id="password" type="password">
                        <!-- Login Error Message -->
                        <?php
                    if(ISSET($invalidLoginError)){
                        if($invalidLoginError == true){
                    ?>
                        <div class="custom_error_message_container">
                            <i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Incorrect email
                                or
                                password</span>
                        </div>
                        <?php
                        }
                    } if(ISSET($emptyLoginError)){
                        if($emptyLoginError == true){
                    ?>
                        <div class="custom_error_message_container">
                            <i class="fa fa-exclamation-circle"></i><span class="custom_error_message">Please enter
                                valid email
                                and password</span>
                        </div>
                        <?php
                        }
                    }
                    ?>
                    </div>
                </div>

                <div class="submitButton">
                    <input type="submit" name="SignIn" value="Sign In">
                </div>
                <div class="login_page_links">
                    <p><a href="patientPages/patient_register.php">Register as Patient</a></p>
                    <p><a href="doctorPages/doctor_register.php">Register as Doctor</a></p>
                </div>
            </div>
        </form>
    </div>
</body>

</html>