<?php
session_start();

// Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyPatientUsers.php');

// Include DB config file
require_once('../config/configDB.php');

// Show Navigation Bar
require_once('../navBar/nav_patient.php');

// Define variables
$patientId = $_SESSION['AccountID'];
$uploadDir = '../uploads/medicals/';
$uploadStatus = "";

// Ensure upload directory exists and is writable
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        die('Failed to create upload directory.');
    }
}
if (!is_writable($uploadDir)) {
    die('Upload directory is not writable.');
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['medicalFile'])) {
    $fileName = basename($_FILES['medicalFile']['name']);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    // Check if file already exists
    if (file_exists($targetFilePath)) {
        $uploadStatus = "Sorry, file already exists.";
    } else {
        // Allow certain file formats
        $allowedTypes = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES['medicalFile']['tmp_name'], $targetFilePath)) {
                // Insert file information into the database
                $insertQuery = "INSERT INTO MedicalFiles (PatientID, FileName, FilePath, UploadTime) VALUES (?, ?, ?, NOW())";
                if ($stmt = mysqli_prepare($conn, $insertQuery)) {
                    mysqli_stmt_bind_param($stmt, "iss", $patientId, $fileName, $targetFilePath);
                    if (mysqli_stmt_execute($stmt)) {
                        $uploadStatus = "The file has been uploaded successfully.";
                    } else {
                        $uploadStatus = "Error uploading the file to the database.";
                    }
                }
            } else {
                $uploadStatus = "Sorry, there was an error uploading your file.";
            }
        } else {
            $uploadStatus = "Sorry, only PDF, DOC, DOCX, JPG, JPEG, & PNG files are allowed.";
        }
    }
}

// Fetch previously uploaded files
$fileQuery = "SELECT * FROM MedicalFiles WHERE PatientID = ?";
$fileStmt = mysqli_prepare($conn, $fileQuery);
mysqli_stmt_bind_param($fileStmt, "i", $patientId);
mysqli_stmt_execute($fileStmt);
$fileResult = mysqli_stmt_get_result($fileStmt);
$files = mysqli_fetch_all($fileResult, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Medicals</title>
    <link rel="icon" href="../images/logo_tab.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="uploads_body">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Patient can upload/view their previous medical files</h4>
            </div>
            
            <div class="card-body">
                <!-- Upload Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="medicalFile">Select Medical File to Upload:</label>
                        <input type="file" class="form-control-file" id="medicalFile" name="medicalFile" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>

                <!-- Display Upload Status -->
                <?php if (!empty($uploadStatus)): ?>
                    <div class="alert alert-info mt-3">
                        <?php echo $uploadStatus; ?>
                    </div>
                <?php endif; ?>

                <!-- Display Previously Uploaded Files -->
                <h5 class="mt-5">Previously Uploaded Files:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Upload Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($files)): ?>
                            <?php foreach ($files as $file): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($file['FileName']); ?></td>
                                    <td><?php echo htmlspecialchars($file['UploadTime']); ?></td>
                                    <td><a href="<?php echo htmlspecialchars($file['FilePath']); ?>" target="_blank" class="btn btn-info btn-sm">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No files uploaded yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
