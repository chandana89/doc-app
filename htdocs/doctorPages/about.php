<?php
session_start();

// Authenticate Users
require_once('../verify/allowOnlyLoggedInUsers.php');
require_once('../verify/allowOnlyDoctorUsers.php');

// Include DB config file
require_once('../config/configDB.php');

// Show Navigation Bar
require_once('../navBar/nav_doctor.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About & Contact Us - Medi Connect</title>
    <link rel="icon" href="../images/logo_tab.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        
    </style>
</head>

<body class="about_doctor_body">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="about_contact_wrapper">
                    <h2>About Medi Connect</h2>
                    <p>Medi Connect is a comprehensive platform designed to revolutionize communication and management processes in the healthcare industry. Our mission is to provide innovative solutions that enhance patient care, improve efficiency, and facilitate collaboration among healthcare professionals.</p>
                    <p>With features such as appointment scheduling, prescription management, patient records management, Medi Connect empowers doctors and medical staff to deliver high-quality care while simplifying administrative tasks.</p>
                    <p>Key benefits of using Medi Connect include:</p>
                    <ul>
                        <li>Streamlined Workflow: Simplify appointment scheduling, prescription refills, and patient record management.</li>
                        <li>Enhanced Communication: Facilitate secure messaging between healthcare providers and patients, fostering better communication and coordination of care.</li>
                        <li>Improved Patient Care: Access comprehensive patient records, medication history, and treatment plans to provide personalized and effective care.</li>
                        <li>Efficient Collaboration: Enable collaboration among healthcare teams, allowing for better coordination and faster decision-making.</li>
                    </ul>

                    <h2>Contact Us</h2>
                    <div class="contact_info">
                        <p>If you have any questions, feedback, or inquiries, please feel free to contact us:</p>
                        <ul>
                            <li><strong>Email:</strong> info@mediconnect.com</li>
                            <li><strong>Phone:</strong> 1-800-MEDI-CON</li>
                            <li><strong>Address:</strong> 123 Medical Street, Cityville, State, Zip</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
