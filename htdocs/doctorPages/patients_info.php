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


$medicalfiles = [];
$account_id = $_SESSION['AccountID']; 

$query = "SELECT DoctorID FROM Doctor WHERE AccountID = '" . $account_id . "'";
$rs = mysqli_query($conn, $query);
$doctorIdData = mysqli_fetch_row($rs);
$doctorID = $doctorIdData[0];


$query_count = "";
if (!empty($search)) {
    $query_count = "SELECT COUNT(*) 
                    FROM MedicalFiles mf
                    JOIN Patient p ON mf.PatientID = p.PatientID
                    WHERE CONCAT(p.firstName, ' ', p.lastName) LIKE '%$search%'";
} else {
    $query_count = "SELECT COUNT(*) 
                    FROM MedicalFiles mf
                    JOIN Patient p ON mf.PatientID = p.PatientID";
}


$rs_count = mysqli_query($conn, $query_count);
$total_medicalfiles = mysqli_fetch_row($rs_count)[0];
$total_pages = ceil($total_medicalfiles / $limit);


$query_medicalfiles = "";
if (!empty($search)) {
    $query_medicalfiles = "SELECT p.firstName, p.lastName, p.Gender, p.ContactNumber, mf.FileName, mf.FilePath, DATE_FORMAT(mf.UploadTime, '%d-%m-%Y %r') AS UploadTime
                            FROM MedicalFiles mf
                            JOIN Patient p ON mf.PatientID = p.PatientID
                            WHERE CONCAT(p.firstName, ' ', p.lastName) LIKE '%$search%'
                            LIMIT $limit OFFSET $offset";
} else {
    $query_medicalfiles = "SELECT p.firstName, p.lastName, p.Gender, p.ContactNumber, mf.FileName, mf.FilePath, DATE_FORMAT(mf.UploadTime, '%d-%m-%Y %r') AS UploadTime
                            FROM MedicalFiles mf
                            JOIN Patient p ON mf.PatientID = p.PatientID
                            LIMIT $limit OFFSET $offset";
}

$rs = mysqli_query($conn, $query_medicalfiles);

if ($rs) {
    while ($row = mysqli_fetch_assoc($rs)) {
        $medicalfiles[] = $row;
    }
} else {
    echo "Error fetching medicalfiles.";
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

           
        });
    </script>
</head>

<body class="patients_info_body">
    <?php
    // Show Navigation Bar
    require_once('../navBar/nav_doctor.php');
    ?>
    <div class="prescription_wrapper">
        <div class="content">
            <h2>Patients Medical History</h2>
            <div class="search-container">
                <form id="searchForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                    <div class="form-group">
                        <input type="text" class="form-control" id="searchInput" name="search" placeholder="Search by patient name" value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </form>
            </div>
            <div class="card-container">
                    <?php if (count($medicalfiles) > 0) : ?>
                        <?php foreach ($medicalfiles as $medicalfile) : ?>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Patient Name: <?php echo htmlspecialchars($medicalfile['firstName'] . ' ' . $medicalfile['lastName']); ?></h5>
                                    <p class="card-text"><strong>Gender:</strong> <?php echo htmlspecialchars($medicalfile['Gender']); ?></p>
                                    <p class="card-text"><strong>Contact Number:</strong> <?php echo htmlspecialchars($medicalfile['ContactNumber']); ?></p>
                                    <p class="card-text"><strong>File Name:</strong> <?php echo htmlspecialchars($medicalfile['FileName']); ?></p>
                                    <p class="card-text"><strong>Uploaded Time:</strong> <?php echo htmlspecialchars($medicalfile['UploadTime']); ?></p>
                                    <a href="<?php echo htmlspecialchars($medicalfile['FilePath']); ?>" class="btn btn-primary">View Medical File</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No medical files found.</p>
                    <?php endif; ?>
                </div>

            <div class="d-flex justify-content-center mt-3">
                <div class="pagination">
                    <?php if ($total_medicalfiles > 5) : ?>
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



