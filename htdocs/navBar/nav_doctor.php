<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
   
    <link rel="icon" href="../images/logo_tab.jpg">
    <link rel="stylesheet" href="../css/nav.css">
</head>

<body>

<header class="top-bar">
        <div class="container nav_doctor">
            <div class="logo">
                <a href="#"><img src="../images/symbol.png" alt="Medi Connect"></a>
            </div>
            <nav class="menu">
                <a href="doctor_home.php">Appointments</a>
                <a href="patients_info.php">Patients</a>
                <a href="prescriptions.php">Prescriptions</a>
                <a href="about.php">About</a>
                <div class="dropdown">
                    <a class="dropbtn"><i class="fa-regular fa-circle-user"></i></a>
                    <div class="dropdown-content">
                        <a href="doctor_account.php">Account Settings</a>
                        <!-- <a href="upload_medicals.php">Upload Medical History</a> -->
                        <a href="../user_logout.php">User Logout</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

</body>

</html>