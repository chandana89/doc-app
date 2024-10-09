<?php
session_start();

// Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyPatientUsers.php');

// Include DB config file
require_once('../config/configDB.php');

// Show Navigation Bar
require_once('../navBar/nav_patient.php');

// Get patient ID from session
$patientId = $_SESSION['AccountID'];

// Handle cancellation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancelAppointmentId'])) {
    $appointmentId = $_POST['cancelAppointmentId'];
    
    // Update the appointment status in PatientAppointment table
    $cancelQuery = "UPDATE PatientAppointment SET isBooked = 0 WHERE PatientAppointmentID = ? AND PatientID = ?";
    if ($stmtCancel = mysqli_prepare($conn, $cancelQuery)) {
        mysqli_stmt_bind_param($stmtCancel, "ii", $appointmentId, $patientId);
        if (mysqli_stmt_execute($stmtCancel)) {
            $cancelStatus = "Appointment cancelled successfully.";
        } else {
            $cancelStatus = "Error cancelling the appointment.";
        }
    }
}

// Fetch the patient's appointment history
$historyQuery = "SELECT pa.PatientAppointmentID, pa.AppointmentTime, isBooked, h.HospitalName, d.FirstName
    AS DoctorFirstName, d.LastName AS DoctorLastName
    FROM PatientAppointment pa
    JOIN Hospital h ON pa.HospitalID = h.HospitalID
    JOIN Doctor d ON pa.DoctorID = d.DoctorID
    WHERE pa.PatientID = ?
    ORDER BY pa.AppointmentTime DESC";
$stmtHistory = mysqli_prepare($conn, $historyQuery);
mysqli_stmt_bind_param($stmtHistory, "i", $patientId);
mysqli_stmt_execute($stmtHistory);
$resultHistory = mysqli_stmt_get_result($stmtHistory);
$appointments = mysqli_fetch_all($resultHistory, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Visits History</title>
    <link rel="icon" href="../images/logo_tab.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="history_body">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Previous and Upcoming Visits</h4>
            </div>
            
            <div class="card-body">
                <?php if (isset($cancelStatus)): ?>
                    <div class="alert alert-info">
                        <?php echo $cancelStatus; ?>
                    </div>
                <?php endif; ?>
        
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Hospital</th>
                            <th>Doctor</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($appointment['AppointmentTime']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['HospitalName']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['DoctorFirstName'] . ' ' . $appointment['DoctorLastName']); ?></td>
                                    <td>
                                        <?php
                                        $appointmentTime = new DateTime($appointment['AppointmentTime']);
                                        $now = new DateTime();
                                        if ($appointmentTime > $now && $appointment['isBooked'] == 1): ?>
                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display:inline;">
                                                <input type="hidden" name="cancelAppointmentId" value="<?php echo $appointment['PatientAppointmentID']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                            </form>
                                        <?php elseif ($appointmentTime > $now && $appointment['isBooked'] == 0): ?>
                                            <span class="text-warning">Cancelled</span>
                                        <?php else: ?>
                                            <span class="text-muted">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No appointments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
