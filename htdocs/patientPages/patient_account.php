<?php
session_start();

// Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyPatientUsers.php');

// Include DB config file
require_once('../config/configDB.php');

// Show Navigation Bar
require_once('../navBar/nav_patient.php');

$accountID = $_SESSION['AccountID'];
$email = $_SESSION['email'];

$query = "SELECT * FROM Patient WHERE AccountID = '" . $accountID . "'";
$rs = mysqli_query($conn, $query);
$patientData = mysqli_fetch_assoc($rs);

// Update patient data if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientID = $_POST['PatientID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $contactNumber = $_POST['contactNumber'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $address = $_POST['address'];
    $emergencyContactNumber = $_POST['emergencyContactNumber'];

    $update_query = "UPDATE Patient SET FirstName = ?, LastName = ?, Gender = ?, ContactNumber = ?, DateOfBirth = ?, Address = ?, EmergencyContactNumber = ? WHERE PatientID = ?";
    if ($stmt = mysqli_prepare($conn, $update_query)) {
        mysqli_stmt_bind_param($stmt, "sssssssi", $firstName, $lastName, $gender, $contactNumber, $dateOfBirth, $address, $emergencyContactNumber, $patientID);
        if (mysqli_stmt_execute($stmt)) {
            // Successfully updated the patient's information
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Account</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="patient_account_body">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Patient Account</h4>
            </div>

            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <input type="hidden" name="PatientID" value="<?php echo htmlspecialchars($patientData['PatientID']); ?>">

                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($patientData['FirstName']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($patientData['LastName']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="Male" <?php echo ($patientData['Gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($patientData['Gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="text" class="form-control" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($patientData['ContactNumber']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="dateOfBirth">Date of Birth</label>
                        <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($patientData['DateOfBirth']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($patientData['Address']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="emergencyContactNumber">Emergency Contact Number</label>
                        <input type="text" class="form-control" id="emergencyContactNumber" name="emergencyContactNumber" value="<?php echo htmlspecialchars($patientData['EmergencyContactNumber']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
