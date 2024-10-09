<?php
session_start();

// Include DB config file
require_once('../config/configDB.php');

// $successMessage = "";
// $errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
    $param_roleId = 1;
    // $successMessage =  0;


    $param_hashPassword = hash('sha256', $_POST["password"]);

    
    date_default_timezone_set("Australia/Sydney");
    

    
    if(ISSET($_POST["RegisterPatient"])){

        $email = trim($_POST['email']);
        $query = "SELECT AccountID FROM Account WHERE EmailAddress = ?";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errorMessage= "Account already exists!!";
            } else {
                $sql1 = "INSERT INTO Account (EmailAddress, Password, VerificationStatus, RoleID) VALUES (?,?,?,?)";
                if ($stmt1 = mysqli_prepare($conn, $sql1)) {
                    mysqli_stmt_bind_param($stmt1, "ssss", $param_email, $param_hashPassword, $param_status, $param_roleId);
                    $param_email = trim($_POST["email"]);
                    $param_status = 'verified';
                    if (mysqli_stmt_execute($stmt1)) {
                    } else {
                        echo "Please check that you have entered the correct details.";
                    }
                    mysqli_stmt_close($stmt1);
                
                
        
                    $currentUserEmail = trim($_POST["email"]);

                
                    $query3 = "SELECT AccountID FROM Account WHERE EmailAddress = '" . $currentUserEmail . "'";
                    $rs3 = mysqli_query($conn, $query3);
                    $accountIdData = mysqli_fetch_row($rs3);
                    $param_accountId = $accountIdData[0];

                    $sql2 = "INSERT INTO Patient (FirstName, LastName, Gender, Address,
                        ContactNumber, DateOfBirth, CountryID, EmergencyContactNumber, AccountID) VALUES (?,?,?,?,?,?,?,?,?)";

                    
                    if ($stmt2 = mysqli_prepare($conn, $sql2)) {
                        mysqli_stmt_bind_param($stmt2, "sssssssss", $param_firstName, $param_lastName, $param_gender, $param_address, $param_contact, $param_DateOfBirth, $param_CountryID, $param_eContact, $param_accountId);

                    
                        $param_firstName = trim($_POST["firstName"]);
                        $param_lastName = trim($_POST["lastName"]);
                        $param_gender =trim($_POST["gender"]);
                        $param_address = trim($_POST["address"]);
                        $param_contact = trim($_POST["contactNo"]);
                        $param_DateOfBirth = trim($_POST["dob"]);
                        $param_CountryID = trim($_POST["nationality"]);
                        $param_eContact = trim($_POST["eContactNo"]);
                    
                        
                        if (mysqli_stmt_execute($stmt2)) {
                            
                            // $_SESSION['successMessage'] = "Success!";
                            $successMessage ="Registered Successfully!!";
                            // header("Location: " . $_SERVER['PHP_SELF']);
                            // exit();
                        } else {
                            
                            $errorMessage= "Please check that you have entered the correct details.";
                        }
                        mysqli_stmt_close($stmt2);
                    }else {
                        $errorMessage = "Error fetching account ID.";
                    }
                }else {
                    $errorMessage = "Please check that you have entered the correct details.";
                }
                // mysqli_stmt_close($stmt1);

            }
            mysqli_stmt_close($stmt);
        }
        // mysqli_close($conn);
        // exit();
    }
}


//Get Country List
$query2 = "SELECT CountryID, CountryName FROM Country";
$rs2 = mysqli_query($conn, $query2);
$countryData = mysqli_fetch_all($rs2);


mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>Register | Patient </title>
  
    <link rel="icon" href="../images/logo_tab.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="register_patient_body">
        
    <div class="register_wrapper">
    <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <?php echo $errorMessage; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <?php echo $successMessage; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        <form id="formPatientRegister" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
            onsubmit="return validateForm()">
            <div class="container_register SRContainer">
                <div class="register_heading">
                   
                    <img src="../images/symbol.png" alt="Medi Connect">
                    <h2>Patient Registration</h2>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="firstName">First Name<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <input name="firstName" id="firstName" type="text" placeholder="Enter First Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="lastName">Last Name<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <input name="lastName" id="lastName" type="text" placeholder="Enter Last Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="email">Email<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <input name="email" id="email" type="text" placeholder="Enter Email">
                    </div>
                </div>
              
                <div class="row">
                    <div class="col-25">
                        <label for="gender">Gender<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <input type="radio" id="male" name="gender" value="male">
                        <label for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="female">
                        <label for="male">Female</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="address">Address<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <input name="address" id="address" type="text" placeholder="Enter Address">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="contactNo">Contact Number<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <input name="contactNo" id="contactNo" type="text" placeholder="Enter Contact Number">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="dob">Date of Birth<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <input name="dob" id="dob" type="date" placeholder="DD-MM-YYYY">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="nationality">Nationality<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <select name="nationality" id="nationality">
                            <option value="-1">Select Country</option>
                            <?php            
                            foreach($countryData as $country) { ?>
                            <option value="<?php echo $country[0];?>"><?php echo $country[1];?></option>
                            <?php       
                            } 
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="eContactNo">Emergency Contact Number<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <input name="eContactNo" id="eContactNo" type="text" placeholder="Enter Emergency Contact">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="password">Password<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75">
                        <input name="password" id="password" type="password" placeholder="Enter Password">

                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="rePassword">Re-enter Password<span class="required_input_field"> *</span></label>
                    </div>
                    <div class="col-75" id="passwordSwap">
                        <input name="rePassword" id="rePassword" type="password" placeholder="Confirm Password">

                        <div class="custom_alert_info_register">
                            <span class="custom_alert_info_heading_register">Note: </span>
                            <span>Should be at least 8 characters with at least a lowercase, an uppercase, a number and
                                a special character</span>
                        </div>
                    </div>
                </div>

                <div class="submitButton">
                    <input type="submit" name="RegisterPatient" value="Register">
                </div>
                
                <div class="login_page_links">
                    <p>Already have an account? <a href="../user_login.php">Login Here</a></p>
                   
                </div>
            </div>
        </form>

    </div>

    <?php require_once('../validation/validate_patient_register.php'); ?>

</body>

</html>