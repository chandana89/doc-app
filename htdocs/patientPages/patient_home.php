<?php
session_start();

//Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyPatientUsers.php');

// Include DB config file
require_once('../config/configDB.php');

//Show Navigation Bar
require_once('../navBar/nav_patient.php');

// Define variables and initialize with empty values
$location = "";
$hospitalResults = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = trim($_POST["location"]);
    
    // Fetch hospitals based on location
    $query = "SELECT * FROM Hospital WHERE Location LIKE '%$location%'";
    if ($stmt = mysqli_prepare($conn, $query)) {
        // mysqli_stmt_bind_param($stmt, "s", $param_location);
        // $param_location = "%$location%";
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $hospitalResults = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            echo "Error fetching hospitals.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Home</title>
    <link rel="icon" href="../images/logo_tab.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.book-appointment').click(function(){
                var hospitalId = $(this).data('hospital-id');
                window.location.href = 'book_appointment.php?hospitalId=' + hospitalId;
            });
        });
    </script>
</head>

<body class="patient_home_body">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Book an appointment</h4>

                <p>
                    <span>Search hospitals by location and show hospital list and then proceed booking consultation with the selected doctor.</span>
                </p>
            </div>


            <div class="card-body">
                <!-- Search Form -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" class="form-control" id="location" placeholder="Enter location" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <!-- Hospital Results -->
                <?php if (!empty($hospitalResults)): ?>
                    <div class="hospital-results">
                        <h5>Available Hospitals:</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Hospital Name</th>
                                    <th>Address</th>
                                    <th>Location</th>
                                    <th>Office Number</th>
                                    <th>Email Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hospitalResults as $hospital): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($hospital['HospitalName']); ?></td>
                                        <td><?php echo htmlspecialchars($hospital['Address']); ?></td>
                                        <td><?php echo htmlspecialchars($hospital['Location']); ?></td>
                                        <td><?php echo htmlspecialchars($hospital['OfficeNumber']); ?></td>
                                        <td><?php echo htmlspecialchars($hospital['EmailAddress']); ?></td>
                                        <td><button type="button" class="btn btn-success book-appointment" data-hospital-id="<?php echo $hospital['HospitalID']; ?>">Book</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
