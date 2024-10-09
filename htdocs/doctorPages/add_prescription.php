<?php
session_start();

// Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyDoctorUsers.php');

// Include DB config file
require_once('../config/configDB.php');

// Show Navigation Bar
require_once('../navBar/nav_doctor.php');

$patientAppointmentID = $_GET['patientAppointmentID'] ?? null;
$patientID = $_GET['patientID'] ?? null;
$doctorID = $_GET['doctorID'] ?? null;

if ($patientAppointmentID && $patientID && $doctorID) {
    // Fetch patient data
    $query_patient = "SELECT firstName, lastName, Gender, ContactNumber FROM Patient WHERE PatientID = ?";
    $stmt_patient = mysqli_prepare($conn, $query_patient);
    mysqli_stmt_bind_param($stmt_patient, "s", $patientID);
    mysqli_stmt_execute($stmt_patient);
    $patientData = mysqli_stmt_get_result($stmt_patient)->fetch_row();
    mysqli_stmt_close($stmt_patient);

    // Fetch patient appointment data
    $query_patientAppointment = "SELECT DATE_FORMAT(AppointmentTime, '%d-%m-%Y %r') FROM PatientAppointment WHERE PatientAppointmentID = ? AND isBooked = true";
    $stmt_patientAppointment = mysqli_prepare($conn, $query_patientAppointment);
    mysqli_stmt_bind_param($stmt_patientAppointment, "s", $patientAppointmentID);
    mysqli_stmt_execute($stmt_patientAppointment);
    $patientAppointmentData = mysqli_stmt_get_result($stmt_patientAppointment)->fetch_row();
    mysqli_stmt_close($stmt_patientAppointment);
} else {
    echo "Patient Appointment ID not found in URL";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prescription = trim($_POST['prescription']);

    if (empty($prescription)) {
        $prescription_err = "Please enter the prescription.";
    } else {
        $check_query = "SELECT PrescriptionID FROM Prescription WHERE PatientAppointmentID = ?";
        $stmt_check = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt_check, "s", $patientAppointmentID);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        $num_rows = mysqli_stmt_num_rows($stmt_check);
        mysqli_stmt_close($stmt_check);

        if ($num_rows > 0) {
            // Update existing prescription
            $sql = "UPDATE Prescription SET Description = ? WHERE PatientAppointmentID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $prescription, $patientAppointmentID);
        } else {
            // Insert new prescription
            $sql = "INSERT INTO Prescription (Description, PatientAppointmentID, PatientID, DoctorID) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $prescription, $patientAppointmentID, $patientID, $doctorID);
        }

        if (mysqli_stmt_execute($stmt)) {
            $successMessage = "Added Prescription successfully!!";
        } else {
            $errorMessage = "Please check that you have entered the prescription.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Prescription</title>
    <link rel="icon" href="../images/logo_tab.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="add_prescription_body">
    <div class="add_prescription_wrapper">
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
            <h2>Add Prescription</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?patientAppointmentID=$patientAppointmentID&patientID=$patientID&doctorID=$doctorID"); ?>">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" disabled value="<?php echo htmlspecialchars($patientData[0]); ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" disabled value="<?php echo htmlspecialchars($patientData[1]); ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <input type="text" class="form-control" id="gender" name="gender" disabled value="<?php echo htmlspecialchars($patientData[2]); ?>">
                </div>
                <div class="form-group">
                    <label for="contactNumber">Contact Number:</label>
                    <input type="text" class="form-control" id="contactNumber" name="contactNumber" disabled value="<?php echo htmlspecialchars($patientData[3]); ?>">
                </div>
                <div class="form-group">
                    <label for="appointmentTime">Appointment Time:</label>
                    <input type="text" class="form-control" id="appointmentTime" name="appointmentTime" disabled value="<?php echo htmlspecialchars($patientAppointmentData[0]); ?>">
                </div>
                <div class="form-group">
                    <label for="prescription">Prescription:</label>
                    <textarea class="form-control <?php echo (!empty($prescription_err)) ? 'is-invalid' : ''; ?>" id="prescription" name="prescription" rows="4"><?php echo htmlspecialchars($_POST['prescription'] ?? ''); ?></textarea>
                    <span class="invalid-feedback"><?php echo $prescription_err ?? ''; ?></span>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='doctor_home.php'">Cancel</button>
            </form>
        </div>
    </div>
    
</body>
</html>
