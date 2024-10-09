<?php
session_start();

// Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyDoctorUsers.php');

// Include DB config file
require_once('../config/configDB.php');

// Show Navigation Bar
require_once('../navBar/nav_doctor.php');

$accountID = $_SESSION['AccountID'];
$email = $_SESSION['email'];

$query = "SELECT DoctorID, firstName, lastName, Gender, Description, SpecializationName, dr.SpecializationID, ContactNumber, DateOfBirth, HospitalID FROM Doctor dr JOIN Specialization sp ON sp.SpecializationID = dr.SpecializationID WHERE dr.AccountID = '" . $accountID . "'";
$rs = mysqli_query($conn, $query);
$doctorData = mysqli_fetch_assoc($rs);

$query2 = "SELECT HospitalID, HospitalName FROM Hospital";
$rs2 = mysqli_query($conn, $query2);
$HospitalData = mysqli_fetch_all($rs2, MYSQLI_ASSOC);

$query3 = "SELECT SpecializationID, SpecializationName FROM Specialization";
$rs3 = mysqli_query($conn, $query3);
$SpecializationData = mysqli_fetch_all($rs3, MYSQLI_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctorID = $_POST['DoctorID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $specialization = $_POST['specialization'];
    $contactNumber = $_POST['contactNumber'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $hospitalID = $_POST['hospital'];
    $description = $_POST['description'];

    $update_query = "UPDATE Doctor SET firstName = ?, lastName = ?, Gender = ?, SpecializationID = ?, ContactNumber = ?, DateOfBirth = ?, HospitalID = ?, Description = ? WHERE DoctorID = ?";
    if ($stmt = mysqli_prepare($conn, $update_query)) {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $firstName, $lastName, $gender, $specialization, $contactNumber, $dateOfBirth, $hospitalID, $description, $doctorID);
        if (mysqli_stmt_execute($stmt)) {
            $successMessage = "Updated user account successfully!!";
            // header("Location: " . $_SERVER['PHP_SELF']);
            // exit();
        } else {
            $errorMessage= "Error updating record: " . mysqli_error($conn);
        }
    } else {
        $errorMessage=  "Error preparing statement: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Doctor Account</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="doctor_account_body">
    <div class="doctor_account_wrapper">
        <div class="form-container">
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
            <h2>Account Settings</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="hidden" name="DoctorID" value="<?php echo htmlspecialchars($doctorData['DoctorID']); ?>">

                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($doctorData['firstName']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($doctorData['lastName']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="Male" <?php echo ($doctorData['Gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($doctorData['Gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="hospital">Hospital</label>
                    <select class="form-control" id="hospital" name="hospital" required>
                        <?php foreach ($HospitalData as $hospital) : ?>
                            <option value="<?php echo $hospital['HospitalID']; ?>" <?php echo ($doctorData['HospitalID'] == $hospital['HospitalID']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($hospital['HospitalName']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <select class="form-control" id="specialization" name="specialization" required>
                        <?php foreach ($SpecializationData as $specialization) : ?>
                            <option value="<?php echo $specialization['SpecializationID']; ?>" <?php echo ($doctorData['SpecializationID'] == $specialization['SpecializationID']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($specialization['SpecializationName']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="form-group">
                    <label for="contactNumber">Contact Number</label>
                    <input type="text" class="form-control" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($doctorData['ContactNumber']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="dateOfBirth">Date of Birth</label>
                    <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($doctorData['DateOfBirth']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Your Profile</label>
                    <textarea type="text" class="form-control" id="description" name="description" required><?php echo htmlspecialchars($doctorData['Description']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</body>

</html>
