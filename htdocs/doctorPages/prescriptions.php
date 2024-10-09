<?php
session_start();

//Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyDoctorUsers.php');

// Include DB config file
require_once('../config/configDB.php');


$limit = 5; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';


$prescriptions = [];
$account_id = $_SESSION['AccountID']; 

$query = "SELECT DoctorID FROM Doctor WHERE AccountID = '" . $account_id . "'";
$rs = mysqli_query($conn, $query);
$doctorIdData = mysqli_fetch_row($rs);
$doctorID = $doctorIdData[0];


$query_count = "";
if (!empty($search)) {
    $query_count = "SELECT COUNT(*) 
                    FROM Prescription pr
                    JOIN PatientAppointment pa ON pr.PatientAppointmentID = pa.PatientAppointmentID
                    JOIN Patient p ON pa.PatientID = p.PatientID
                    WHERE pr.DoctorID = '" . $doctorID . "' AND CONCAT(p.firstName, ' ', p.lastName) LIKE '%$search%'";
} else {
    $query_count = "SELECT COUNT(*) 
                    FROM Prescription pr
                    JOIN PatientAppointment pa ON pr.PatientAppointmentID = pa.PatientAppointmentID
                    WHERE pr.DoctorID = '" . $doctorID . "'";
}

// $query_count = "SELECT COUNT(*) FROM Prescription WHERE DoctorID = '" . $doctorID . "'";
$rs_count = mysqli_query($conn, $query_count);
$total_prescriptions = mysqli_fetch_row($rs_count)[0];
$total_pages = ceil($total_prescriptions / $limit);


$query_prescriptions = "";
if (!empty($search)) {
    $query_prescriptions = "SELECT pr.PrescriptionID, pr.Description, p.PatientID, pa.PatientAppointmentID, DATE_FORMAT(pa.AppointmentTime, '%d-%m-%Y %r') AS AppointmentTime, p.firstName, p.lastName, p.Gender, p.ContactNumber
                            FROM Prescription pr
                            JOIN PatientAppointment pa ON pr.PatientAppointmentID = pa.PatientAppointmentID
                            JOIN Patient p ON pa.PatientID = p.PatientID
                            WHERE pr.DoctorID = '" . $doctorID . "' AND CONCAT(p.firstName, ' ', p.lastName) LIKE '%$search%'
                            LIMIT $limit OFFSET $offset";
} else {
    $query_prescriptions = "SELECT pr.PrescriptionID, pr.Description, p.PatientID, pa.PatientAppointmentID, DATE_FORMAT(pa.AppointmentTime, '%d-%m-%Y %r') AS AppointmentTime, p.firstName, p.lastName, p.Gender, p.ContactNumber
                            FROM Prescription pr
                            JOIN PatientAppointment pa ON pr.PatientAppointmentID = pa.PatientAppointmentID
                            JOIN Patient p ON pa.PatientID = p.PatientID
                            WHERE pr.DoctorID = '" . $doctorID . "'
                            LIMIT $limit OFFSET $offset";
}

$rs = mysqli_query($conn, $query_prescriptions);

if ($rs) {
    while ($row = mysqli_fetch_assoc($rs)) {
        $prescriptions[] = $row;
    }
} else {
    echo "Error fetching prescriptions.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Prescriptions</title>
    <link rel="icon" href="../images/logo_tab.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
   
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var searchInput = document.getElementById("searchInput");

            searchInput.addEventListener("input", function() {
                var searchValue = this.value.trim();
                if (searchValue !== "") {
                    $.ajax({
                        url: "<?php echo $_SERVER['PHP_SELF']; ?>",
                        type: "GET",
                        data: { search: searchValue },
                        success: function(response) {
                            $(".card-container").empty();
                            $(".card-container").append($(response).find(".card-container").html());
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                } else {
                    $.ajax({
                        url: "<?php echo $_SERVER['PHP_SELF']; ?>",
                        type: "GET",
                        success: function(response) {
                            $(".card-container").empty();
                            $(".card-container").append($(response).find(".card-container").html());
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            });

            $(document).on("click", ".view-more", function() {
                $(this).prev(".short-description").toggle();
                $(this).prev().prev(".description").toggle();
                $(this).text($(this).text() == 'View More' ? 'View Less' : 'View More');
            });
        });
    </script>
</head>

<body class="prescription_body">
    <?php
    // Show Navigation Bar
    require_once('../navBar/nav_doctor.php');
    ?>
    <div class="prescription_wrapper">
        <div class="content">
            <h2>Patients Prescriptions</h2>
            <div class="search-container">
                <form id="searchForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                    <div class="form-group">
                        <input type="text" class="form-control" id="searchInput" name="search" placeholder="Search by patient name" value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </form>
            </div>
            <div class="card-container">
                <?php if (count($prescriptions) > 0) : ?>
                    <?php foreach ($prescriptions as $prescription) : ?>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Patient Name: <?php echo htmlspecialchars($prescription['firstName'] . ' ' . $prescription['lastName']); ?></h5>
                                <p class="card-text"><strong>Gender:</strong> <?php echo htmlspecialchars($prescription['Gender']); ?></p>
                                <p class="card-text"><strong>Contact Number:</strong> <?php echo htmlspecialchars($prescription['ContactNumber']); ?></p>
                                <p class="card-text"><strong>Appointment Time:</strong> <?php echo htmlspecialchars($prescription['AppointmentTime']); ?></p>
                                <p class="card-text"><strong>Description:</strong>
                                    <span class="description" style="display: none;"><?php echo htmlspecialchars($prescription['Description']); ?></span>
                                    <span class="short-description"><?php echo htmlspecialchars(substr($prescription['Description'], 0, 100)); ?><?php echo strlen($prescription['Description']) > 100 ? '...' : ''; ?></span>
                                    <?php if (strlen($prescription['Description']) > 100) : ?>
                                        <a href="javascript:void(0);" class="view-more">View More</a>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No prescriptions found.</p>
                <?php endif; ?>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <div class="pagination">
                    <?php if ($total_prescriptions > 5) : ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>



