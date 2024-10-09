<?php
session_start();
error_reporting(E_ALL); 
ini_set('display_errors', 1);
// Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyPatientUsers.php');

// Include DB config file
require_once('../config/configDB.php');



// Get patient ID from session
$patientId = $_SESSION['AccountID'];
date_default_timezone_set("Australia/Sydney");
// Check if this is an Ajax request to fetch available times
if (isset($_GET['action']) && $_GET['action'] == 'fetch_available_times' && isset($_GET['doctorId']) && isset($_GET['date']) && isset($_GET['hospitalId'])) {
    $doctorId = $_GET['doctorId'];
    $selectedDate = $_GET['date'];
    $hospitalId = $_GET['hospitalId'];

    $availableTimes = getAvailableTimes($doctorId, $selectedDate, $hospitalId, $conn);

    header('Content-Type: application/json');
    echo json_encode($availableTimes);
    exit;
}

// Fetch the hospital's details
$hospitalId = isset($_GET['hospitalId']) ? $_GET['hospitalId'] : 1;

$hospitalQuery = "SELECT HospitalName, OpeningTime, ClosingTime, AppointmentWindow FROM Hospital WHERE HospitalID = ?";
$stmtHospital = mysqli_prepare($conn, $hospitalQuery);
mysqli_stmt_bind_param($stmtHospital, "i", $hospitalId);
mysqli_stmt_execute($stmtHospital);
$resultHospital = mysqli_stmt_get_result($stmtHospital);
$hospital = mysqli_fetch_assoc($resultHospital);

$openingTime = new DateTime($hospital['OpeningTime']);
$closingTime = new DateTime($hospital['ClosingTime']);
$appointmentWindow = new DateInterval('PT' . (new DateTime($hospital['AppointmentWindow']))->format('i') . 'M');

// Fetch doctors data
$doctorsQuery = "SELECT DoctorID, FirstName, LastName, Description FROM Doctor WHERE HospitalID = ?";
$stmtDoctors = mysqli_prepare($conn, $doctorsQuery);
mysqli_stmt_bind_param($stmtDoctors, "i", $hospitalId);
mysqli_stmt_execute($stmtDoctors);
$resultDoctors = mysqli_stmt_get_result($stmtDoctors);
$doctors = mysqli_fetch_all($resultDoctors, MYSQLI_ASSOC);

// Function to calculate available times

function getAvailableTimes($doctorId, $selectedDate, $hospitalId, $conn) {
    $hospitalId = isset($_GET['hospitalId']) ? $_GET['hospitalId'] : 1;

    $hospitalQuery = "SELECT HospitalName, OpeningTime, ClosingTime, AppointmentWindow FROM Hospital WHERE HospitalID = ?";
    $stmtHospital = mysqli_prepare($conn, $hospitalQuery);
    mysqli_stmt_bind_param($stmtHospital, "i", $hospitalId);
    mysqli_stmt_execute($stmtHospital);
    $resultHospital = mysqli_stmt_get_result($stmtHospital);
    $hospital = mysqli_fetch_assoc($resultHospital);

    $openingTime = new DateTime($selectedDate . ' ' . $hospital['OpeningTime']);
    $closingTime = new DateTime($selectedDate . ' ' . $hospital['ClosingTime']);
    $appointmentWindow = new DateInterval('PT' . (new DateTime($hospital['AppointmentWindow']))->format('i') . 'M');

    // Generate all possible time slots within the hospital's operating hours
    $currentSlot = clone $openingTime;
    $availableTimes = [];
    while ($currentSlot <= $closingTime) {
        $availableTimes[] = $currentSlot->format('Y-m-d H:i:s');
        $currentSlot->add($appointmentWindow);
    }

    // Fetch booked appointments for the selected date
    $bookedSlotsQuery = "SELECT AppointmentTime FROM PatientAppointment WHERE HospitalID = ? AND DoctorID = ? AND DATE(AppointmentTime) = ? AND isBooked = 1";
    $stmtBookedSlots = mysqli_prepare($conn, $bookedSlotsQuery);
    mysqli_stmt_bind_param($stmtBookedSlots, "iis", $hospitalId, $doctorId, $selectedDate);
    mysqli_stmt_execute($stmtBookedSlots);
    $resultBookedSlots = mysqli_stmt_get_result($stmtBookedSlots);
    $bookedSlots = mysqli_fetch_all($resultBookedSlots, MYSQLI_ASSOC);

    // Remove booked slots from available times
    foreach ($bookedSlots as $bookedSlot) {
        $bookedTime = new DateTime($bookedSlot['AppointmentTime']);
        $bookedTimeString = $bookedTime->format('Y-m-d H:i:s');
        if (($key = array_search($bookedTimeString, $availableTimes)) !== false) {
            unset($availableTimes[$key]);
        }
    }

    // Re-index the array
    $availableTimes = array_values($availableTimes);

    return $availableTimes;
}

    



// Handle booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointmentTime']) && isset($_POST['doctorId'])) {
    $appointmentTime = $_POST['appointmentTime'];
    $doctorId = $_POST['doctorId'];

    // Check if the selected time is still available
    $checkQuery = "SELECT * FROM PatientAppointment WHERE DoctorID = ? AND HospitalID = ? AND AppointmentTime = ? AND isBooked = 1";
    $stmtCheck = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmtCheck, "iis", $doctorId, $hospitalId, $appointmentTime);
    mysqli_stmt_execute($stmtCheck);
    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resultCheck) === 0) {
        // Book the appointment
        $bookQuery = "INSERT INTO PatientAppointment (AppointmentTime, isBooked, HospitalID, PatientID, DoctorID) VALUES (?, 1, ?, ?, ?)";
        $stmtBook = mysqli_prepare($conn, $bookQuery);
        mysqli_stmt_bind_param($stmtBook, "siii", $appointmentTime, $hospitalId, $patientId, $doctorId);

        if (mysqli_stmt_execute($stmtBook)) {
            $bookStatus = "Appointment booked successfully!";
        } else {
            $bookStatus = "Error booking the appointment.";
        }
    } else {
        $bookStatus = "The selected time slot is no longer available.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link rel="icon" href="../images/logo_tab.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".view-more").forEach(function(button) {
                button.addEventListener("click", function() {
                    var doctorId = this.getAttribute("data-id");
                    document.getElementById("short-" + doctorId).classList.add("d-none");
                    document.getElementById("full-" + doctorId).classList.remove("d-none");
                    this.classList.add("d-none");
                    document.querySelector(".view-less[data-id='" + doctorId + "']").classList.remove("d-none");
                });
            });

            document.querySelectorAll(".view-less").forEach(function(button) {
                button.addEventListener("click", function() {
                    var doctorId = this.getAttribute("data-id");
                    document.getElementById("short-" + doctorId).classList.remove("d-none");
                    document.getElementById("full-" + doctorId).classList.add("d-none");
                    this.classList.add("d-none");
                    document.querySelector(".view-more[data-id='" + doctorId + "']").classList.remove("d-none");
                });
            });

            // Fetch available times based on the selected date
            document.querySelectorAll("input[type='date']").forEach(function(datePicker) {
                datePicker.addEventListener("change", function() {
                    var doctorId = this.getAttribute("id").split('-')[1];
                    var selectedDate = this.value;
                    var appointmentTimeSelect = document.getElementById("appointmentTime-" + doctorId);
                    var hospitalId = <?php echo json_encode($hospitalId); ?>;
                    
                    // Fetch available times from the server
                    fetch(`<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?action=fetch_available_times&doctorId=${doctorId}&date=${selectedDate}&hospitalId=${hospitalId}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data)
                            appointmentTimeSelect.innerHTML = "";
                            data.forEach(function(time) {
                                var option = document.createElement("option");
                                option.value = time;
                                option.textContent = time;
                                appointmentTimeSelect.appendChild(option);
                            });
                        });
                });
            });
        });
    </script>
</head>

<body>
    <?php
        //Show Navigation Bar
        require_once('../navBar/nav_patient.php');
    ?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Book an appointment at <?php echo htmlspecialchars($hospital["HospitalName"]); ?></h4>
            </div>
            <div class="card-body">
                <?php if (isset($bookStatus)): ?>
                    <div class="alert alert-info">
                        <?php echo htmlspecialchars($bookStatus); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <?php foreach ($doctors as $doctor): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5><?php echo htmlspecialchars($doctor['FirstName'] . ' ' . $doctor['LastName']); ?></h5>
                                    <p class="description-short" id="short-<?php echo $doctor['DoctorID']; ?>">
                                        <?php echo htmlspecialchars($doctor['Description']); ?>
                                    </p>
                                    <p class="description-full d-none" id="full-<?php echo $doctor['DoctorID']; ?>">
                                        <?php echo htmlspecialchars($doctor['Description']); ?>
                                    </p>
                                    <a href="javascript:void(0);" class="view-more" data-id="<?php echo $doctor['DoctorID']; ?>">View more</a>
                                    <a href="javascript:void(0);" class="view-less d-none" data-id="<?php echo $doctor['DoctorID']; ?>">View less</a>
                                </div>
                                <div class="card-body">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?hospitalId=' . $hospitalId ?>" method="post">
                                        <input type="hidden" name="doctorId" value="<?php echo htmlspecialchars($doctor['DoctorID']); ?>">
                                        <div class="form-group">
                                            <label for="appointmentDate-<?php echo $doctor['DoctorID']; ?>">Select a date:</label>
                                            <input type="date" class="form-control" id="appointmentDate-<?php echo $doctor['DoctorID']; ?>" name="appointmentDate" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="appointmentTime-<?php echo $doctor['DoctorID']; ?>">Select an available time slot:</label>
                                            <select class="form-control" id="appointmentTime-<?php echo $doctor['DoctorID']; ?>" name="appointmentTime" required>
                                                <!-- Options will be populated by JavaScript -->
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Book Appointment</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
   
</body>

</html>
