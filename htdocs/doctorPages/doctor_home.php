<?php
session_start();

//Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyDoctorUsers.php');

// Include DB config file
require_once('../config/configDB.php');



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['date'])) {
    // echo ''. $_POST['date'] .'';
    $date = $_POST['date'];
    $date = date('Y-m-d', strtotime($date));

    $account_id = $_SESSION['AccountID']; 
    // echo $account_id;
    $query = "SELECT DoctorID FROM Doctor WHERE AccountID = '" . $account_id . "'";
    $rs = mysqli_query($conn, $query);
    $doctorIdData = mysqli_fetch_row($rs);
    $doctor_id = $doctorIdData[0];

    if($doctor_id){

    $query_appointment = "SELECT PatientAppointmentID, PatientID, DATE_FORMAT(AppointmentTime, '%r') FROM PatientAppointment WHERE DoctorID = '" . $doctor_id . "' AND isBooked = true AND DATE(AppointmentTime) = '" . $date . "'";
    // echo $query_appointment;
    $rs2 = mysqli_query($conn, $query_appointment);
    $patientAppointmentData = mysqli_fetch_all($rs2);

      
                 echo '<div class="appointment">';
                //  echo $doctor_id;
                 if (mysqli_num_rows($rs2) > 0) {
                    echo '<table class="table">';
                    echo '<thead><tr><th>First Name</th><th>Last Name</th><th>Gender</th><th>Contact Number</th><th>Time</th><th>Add Prescription</th></tr></thead>';
                    echo '<tbody>';
                    foreach($patientAppointmentData as $patientAppointment) {
                        $query_patient = "SELECT firstName, lastName, Gender, ContactNumber FROM Patient WHERE PatientID = '" . $patientAppointment[1] . "'";
                        $rs3 = mysqli_query($conn, $query_patient);
                        $patientData = mysqli_fetch_row($rs3);
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($patientData[0]) . '</td>';
                        echo '<td>' . htmlspecialchars($patientData[1]) . '</td>';
                        echo '<td>' . htmlspecialchars($patientData[2]) . '</td>';
                        echo '<td>' . htmlspecialchars($patientData[3]) . '</td>';
                        echo '<td>' . htmlspecialchars($patientAppointment[2]) . '</td>';
                        echo '<td><a href="add_prescription.php?patientAppointmentID=' . $patientAppointment[0] . '&patientID=' . $patientAppointment[1] . '&doctorID=' . $doctor_id . '"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>No appointments scheduled at this moment.</p>';
                }
               
                 echo '</div>';
           
    }
    
   exit();
  
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Doctor Home</title>
    <link rel="icon" href="../images/logo_tab.jpg">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        $(document).ready(function(){
            var today = new Date();

            
            $('#datepicker').datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: true,
                startDate: today,
                defaultViewDate: { year: today.getFullYear(), month: today.getMonth(), day: today.getDate() }
            }).datepicker('setDate', today); 

    
            $('#datepicker, .input-group-text').click(function() {
                $('#datepicker').datepicker('show');
            });

            $('#datepicker').on('changeDate', function() {
                var selectedDate = $('#datepicker').val();
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: { date: selectedDate },
                    success: function(response) {
                        $('#appointments').html(response);
                    }
                });
            });
            var formattedToday = today.toISOString().split('T')[0];
        
            $.ajax({
                url: '',
                type: 'POST',
                data: { date: formattedToday },
                success: function(response) {
                    $('#appointments').html(response);
                }
            });
        });
    </script>
</head>

<body class="doctor_home_body">
    <?php
    //Show Navigation Bar
    require_once('../navBar/nav_doctor.php');
    ?>
    <div class="doctor_home_main d-flex justify-content-center align-items-center">
        <div class="container datepicker-table-container">
            <div class="row">
                <div class="col-md-8">
                    <div id="appointments"></div>
                </div>
                <div class="col-md-4">
                    <div class="datepicker-container">
                        <div class="form-group">
                            <label for="datepicker">Select a Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="datepicker" placeholder="Choose a date">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>