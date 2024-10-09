
START TRANSACTION;

CREATE DATABASE IF NOT EXISTS medi_connect;

USE medi_connect;

-- SET time_zone = 'Australia/Sydney';

DROP TABLE IF EXISTS `Role`;
DROP TABLE IF EXISTS `Account`;
DROP TABLE IF EXISTS `Patient`;
DROP TABLE IF EXISTS `Doctor`;
DROP TABLE IF EXISTS `Admin`;
DROP TABLE IF EXISTS `Hospital`;
DROP TABLE IF EXISTS `Country`;
DROP TABLE IF EXISTS `PatientAppointment`;
DROP TABLE IF EXISTS `Prescription`;
DROP TABLE IF EXISTS `Specialization`;
DROP TABLE IF EXISTS `MedicalFiles`;

CREATE TABLE IF NOT EXISTS `Country` (
  `CountryID` INT(11) NOT NULL AUTO_INCREMENT,
  `CountryCode` varchar(2) NOT NULL,
  `CountryName` varchar(255) NOT NULL,
  PRIMARY KEY (`CountryID`)
);

CREATE TABLE IF NOT EXISTS `Specialization` (
  `SpecializationID` INT(11) NOT NULL AUTO_INCREMENT,
  `SpecializationName` varchar(255) NOT NULL,
  PRIMARY KEY (`SpecializationID`)
);

CREATE TABLE IF NOT EXISTS `Role` (
  `RoleID` INT(11) NOT NULL AUTO_INCREMENT,
  `RoleName` ENUM('patient', 'doctor') NOT NULL,
  PRIMARY KEY (`RoleID`)
);

CREATE TABLE IF NOT EXISTS `Hospital` (
  `HospitalID` INT(11) NOT NULL AUTO_INCREMENT,
  `HospitalName` varchar(255) NOT NULL,
  `Address` varchar(1000) NOT NULL,
  `Location` varchar(100) NOT NULL,
  `OfficeNumber` varchar(100) NOT NULL,
  `EmailAddress` varchar(255) NOT NULL,
  `OpeningTime` TIME NOT NULL,
  `ClosingTime` TIME NOT NULL,
  `AppointmentWindow` TIME NOT NULL,
  PRIMARY KEY (`HospitalID`)
);

CREATE TABLE IF NOT EXISTS `Account` (
  `AccountID` INT(11) NOT NULL AUTO_INCREMENT,
  `EmailAddress` varchar(255) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `VerificationStatus` ENUM('verified', 'unverified') NOT NULL DEFAULT 'verified',
  `CreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `LastModifiedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `RoleID` INT(11) NOT NULL,
  PRIMARY KEY (`AccountID`),
  FOREIGN KEY (`RoleID`) REFERENCES Role(`RoleID`)
);

CREATE TABLE IF NOT EXISTS `Patient` (
  `PatientID` INT(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Gender` varchar(15) NOT NULL,
  `Address` varchar(1000) NOT NULL,
  `ContactNumber` varchar(100) NOT NULL,
  `DateOfBirth` DATE NOT NULL,
  `CountryID` INT(11) NOT NULL,
  `EmergencyContactNumber` varchar(100) NOT NULL,
  `CreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `LastModifiedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `AccountID` INT(11) NOT NULL,
  PRIMARY KEY (`PatientID`),
  FOREIGN KEY (`CountryID`) REFERENCES Country(`CountryID`),
  FOREIGN KEY (`AccountID`) REFERENCES Account(`AccountID`)
);

CREATE TABLE IF NOT EXISTS `Doctor` (
`DoctorID` INT(11) NOT NULL AUTO_INCREMENT,
`FirstName` varchar(100) NOT NULL,
`LastName` varchar(100) NOT NULL,
`ContactNumber` varchar(100) NOT NULL,
`DateOfBirth` DATE NOT NULL,
`Gender` varchar(15) NOT NULL,
`Description` varchar(4000) NOT NULL,
`CreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`LastModifiedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
`AccountID` INT(11) NOT NULL,
`HospitalID` INT(11) NOT NULL,
`SpecializationID` INT(11) NOT NULL,
PRIMARY KEY (`DoctorID`),
FOREIGN KEY (`AccountID`) REFERENCES Account (`AccountID`),
FOREIGN KEY (`HospitalID`) REFERENCES Hospital(`HospitalID`),
FOREIGN KEY (`SpecializationID`) REFERENCES Specialization(`SpecializationID`)
);

CREATE TABLE IF NOT EXISTS `PatientAppointment` (
  `PatientAppointmentID` INT(11) NOT NULL AUTO_INCREMENT,
  `AppointmentTime` DATETIME NOT NULL,
  `isBooked` BOOLEAN NOT NULL DEFAULT 0,
  `HospitalID` INT(11) NOT NULL,
  `PatientID` INT(11) NOT NULL,
  `DoctorID` INT(11) NOT NULL,
  PRIMARY KEY (`PatientAppointmentID`),
  FOREIGN KEY (`HospitalID`) REFERENCES Hospital(`HospitalID`),
  FOREIGN KEY (`PatientID`) REFERENCES Patient(`PatientID`),
  FOREIGN KEY (`DoctorID`) REFERENCES Doctor(`DoctorID`)
);

CREATE TABLE IF NOT EXISTS `Prescription` (
  `PrescriptionID` INT(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(4000) NOT NULL,
  `PatientAppointmentID` INT(11) NOT NULL,
  `PatientID` INT(11) NOT NULL,
  `DoctorID` INT(11) NOT NULL,
  PRIMARY KEY (`PrescriptionID`),
  FOREIGN KEY (`PatientAppointmentID`) REFERENCES PatientAppointment(`PatientAppointmentID`),
  FOREIGN KEY (`PatientID`) REFERENCES Patient(`PatientID`),
  FOREIGN KEY (`DoctorID`) REFERENCES Doctor(`DoctorID`)
);

CREATE TABLE IF NOT EXISTS `MedicalFiles` (
    FileID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT NOT NULL,
    FileName VARCHAR(255) NOT NULL,
    FilePath VARCHAR(255) NOT NULL,
    UploadTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (PatientID) REFERENCES Patient(PatientID)
);

-- Insert into Country
INSERT INTO `Country` VALUES (null, 'AF', 'Afghanistan');
INSERT INTO `Country` VALUES (null, 'AL', 'Albania');
INSERT INTO `Country` VALUES (null, 'DZ', 'Algeria');
INSERT INTO `Country` VALUES (null, 'AS', 'American Samoa');
INSERT INTO `Country` VALUES (null, 'AD', 'Andorra');
INSERT INTO `Country` VALUES (null, 'AO', 'Angola');
INSERT INTO `Country` VALUES (null, 'AI', 'Anguilla');
INSERT INTO `Country` VALUES (null, 'AQ', 'Antarctica');
INSERT INTO `Country` VALUES (null, 'AG', 'Antigua and Barbuda');
INSERT INTO `Country` VALUES (null, 'AR', 'Argentina');
INSERT INTO `Country` VALUES (null, 'AM', 'Armenia');
INSERT INTO `Country` VALUES (null, 'AW', 'Aruba');
INSERT INTO `Country` VALUES (null, 'AU', 'Australia');
INSERT INTO `Country` VALUES (null, 'AT', 'Austria');
INSERT INTO `Country` VALUES (null, 'AZ', 'Azerbaijan');
INSERT INTO `Country` VALUES (null, 'BS', 'Bahamas');
INSERT INTO `Country` VALUES (null, 'BH', 'Bahrain');
INSERT INTO `Country` VALUES (null, 'BD', 'Bangladesh');
INSERT INTO `Country` VALUES (null, 'BB', 'Barbados');
INSERT INTO `Country` VALUES (null, 'BY', 'Belarus');
INSERT INTO `Country` VALUES (null, 'BE', 'Belgium');
INSERT INTO `Country` VALUES (null, 'BZ', 'Belize');
INSERT INTO `Country` VALUES (null, 'BJ', 'Benin');
INSERT INTO `Country` VALUES (null, 'BM', 'Bermuda');
INSERT INTO `Country` VALUES (null, 'BT', 'Bhutan');
INSERT INTO `Country` VALUES (null, 'BO', 'Bolivia');
INSERT INTO `Country` VALUES (null, 'BA', 'Bosnia and Herzegovina');
INSERT INTO `Country` VALUES (null, 'BW', 'Botswana');
INSERT INTO `Country` VALUES (null, 'BV', 'Bouvet Island');
INSERT INTO `Country` VALUES (null, 'BR', 'Brazil');
INSERT INTO `Country` VALUES (null, 'IO', 'British Indian Ocean Territory');
INSERT INTO `Country` VALUES (null, 'BN', 'Brunei Darussalam');
INSERT INTO `Country` VALUES (null, 'BG', 'Bulgaria');
INSERT INTO `Country` VALUES (null, 'BF', 'Burkina Faso');
INSERT INTO `Country` VALUES (null, 'BI', 'Burundi');
INSERT INTO `Country` VALUES (null, 'KH', 'Cambodia');
INSERT INTO `Country` VALUES (null, 'CM', 'Cameroon');
INSERT INTO `Country` VALUES (null, 'CA', 'Canada');
INSERT INTO `Country` VALUES (null, 'CV', 'Cape Verde');
INSERT INTO `Country` VALUES (null, 'KY', 'Cayman Islands');
INSERT INTO `Country` VALUES (null, 'CF', 'Central African Republic');
INSERT INTO `Country` VALUES (null, 'TD', 'Chad');
INSERT INTO `Country` VALUES (null, 'CL', 'Chile');
INSERT INTO `Country` VALUES (null, 'CN', 'China');
INSERT INTO `Country` VALUES (null, 'CX', 'Christmas Island');
INSERT INTO `Country` VALUES (null, 'CC', 'Cocos (Keeling) Islands');
INSERT INTO `Country` VALUES (null, 'CO', 'Colombia');
INSERT INTO `Country` VALUES (null, 'KM', 'Comoros');
INSERT INTO `Country` VALUES (null, 'CD', 'Democratic Republic of the Congo');
INSERT INTO `Country` VALUES (null, 'CG', 'Republic of Congo');
INSERT INTO `Country` VALUES (null, 'CK', 'Cook Islands');
INSERT INTO `Country` VALUES (null, 'CR', 'Costa Rica');
INSERT INTO `Country` VALUES (null, 'HR', 'Croatia (Hrvatska)');
INSERT INTO `Country` VALUES (null, 'CU', 'Cuba');
INSERT INTO `Country` VALUES (null, 'CY', 'Cyprus');
INSERT INTO `Country` VALUES (null, 'CZ', 'Czech Republic');
INSERT INTO `Country` VALUES (null, 'DK', 'Denmark');
INSERT INTO `Country` VALUES (null, 'DJ', 'Djibouti');
INSERT INTO `Country` VALUES (null, 'DM', 'Dominica');
INSERT INTO `Country` VALUES (null, 'DO', 'Dominican Republic');
INSERT INTO `Country` VALUES (null, 'TL', 'East Timor');
INSERT INTO `Country` VALUES (null, 'EC', 'Ecuador');
INSERT INTO `Country` VALUES (null, 'EG', 'Egypt');
INSERT INTO `Country` VALUES (null, 'SV', 'El Salvador');
INSERT INTO `Country` VALUES (null, 'GQ', 'Equatorial Guinea');
INSERT INTO `Country` VALUES (null, 'ER', 'Eritrea');
INSERT INTO `Country` VALUES (null, 'EE', 'Estonia');
INSERT INTO `Country` VALUES (null, 'ET', 'Ethiopia');
INSERT INTO `Country` VALUES (null, 'FK', 'Falkland Islands (Malvinas)');
INSERT INTO `Country` VALUES (null, 'FO', 'Faroe Islands');
INSERT INTO `Country` VALUES (null, 'FJ', 'Fiji');
INSERT INTO `Country` VALUES (null, 'FI', 'Finland');
INSERT INTO `Country` VALUES (null, 'FR', 'France');
INSERT INTO `Country` VALUES (null, 'FX', 'France, Metropolitan');
INSERT INTO `Country` VALUES (null, 'GF', 'French Guiana');
INSERT INTO `Country` VALUES (null, 'PF', 'French Polynesia');
INSERT INTO `Country` VALUES (null, 'TF', 'French Southern Territories');
INSERT INTO `Country` VALUES (null, 'GA', 'Gabon');
INSERT INTO `Country` VALUES (null, 'GM', 'Gambia');
INSERT INTO `Country` VALUES (null, 'GE', 'Georgia');
INSERT INTO `Country` VALUES (null, 'DE', 'Germany');
INSERT INTO `Country` VALUES (null, 'GH', 'Ghana');
INSERT INTO `Country` VALUES (null, 'GI', 'Gibraltar');
INSERT INTO `Country` VALUES (null, 'GG', 'Guernsey');
INSERT INTO `Country` VALUES (null, 'GR', 'Greece');
INSERT INTO `Country` VALUES (null, 'GL', 'Greenland');
INSERT INTO `Country` VALUES (null, 'GD', 'Grenada');
INSERT INTO `Country` VALUES (null, 'GP', 'Guadeloupe');
INSERT INTO `Country` VALUES (null, 'GU', 'Guam');
INSERT INTO `Country` VALUES (null, 'GT', 'Guatemala');
INSERT INTO `Country` VALUES (null, 'GN', 'Guinea');
INSERT INTO `Country` VALUES (null, 'GW', 'Guinea-Bissau');
INSERT INTO `Country` VALUES (null, 'GY', 'Guyana');
INSERT INTO `Country` VALUES (null, 'HT', 'Haiti');
INSERT INTO `Country` VALUES (null, 'HM', 'Heard and Mc Donald Islands');
INSERT INTO `Country` VALUES (null, 'HN', 'Honduras');
INSERT INTO `Country` VALUES (null, 'HK', 'Hong Kong');
INSERT INTO `Country` VALUES (null, 'HU', 'Hungary');
INSERT INTO `Country` VALUES (null, 'IS', 'Iceland');
INSERT INTO `Country` VALUES (null, 'IN', 'India');
INSERT INTO `Country` VALUES (null, 'IM', 'Isle of Man');
INSERT INTO `Country` VALUES (null, 'ID', 'Indonesia');
INSERT INTO `Country` VALUES (null, 'IR', 'Iran (Islamic Republic of)');
INSERT INTO `Country` VALUES (null, 'IQ', 'Iraq');
INSERT INTO `Country` VALUES (null, 'IE', 'Ireland');
INSERT INTO `Country` VALUES (null, 'IL', 'Israel');
INSERT INTO `Country` VALUES (null, 'IT', 'Italy');
INSERT INTO `Country` VALUES (null, 'CI', 'Ivory Coast');
INSERT INTO `Country` VALUES (null, 'JE', 'Jersey');
INSERT INTO `Country` VALUES (null, 'JM', 'Jamaica');
INSERT INTO `Country` VALUES (null, 'JP', 'Japan');
INSERT INTO `Country` VALUES (null, 'JO', 'Jordan');
INSERT INTO `Country` VALUES (null, 'KZ', 'Kazakhstan');
INSERT INTO `Country` VALUES (null, 'KE', 'Kenya');
INSERT INTO `Country` VALUES (null, 'KI', 'Kiribati');
INSERT INTO `Country` VALUES (null, 'KP', 'Korea, Democratic People''s Republic of');
INSERT INTO `Country` VALUES (null, 'KR', 'Korea, Republic of');
INSERT INTO `Country` VALUES (null, 'XK', 'Kosovo');
INSERT INTO `Country` VALUES (null, 'KW', 'Kuwait');
INSERT INTO `Country` VALUES (null, 'KG', 'Kyrgyzstan');
INSERT INTO `Country` VALUES (null, 'LA', 'Lao People''s Democratic Republic');
INSERT INTO `Country` VALUES (null, 'LV', 'Latvia');
INSERT INTO `Country` VALUES (null, 'LB', 'Lebanon');
INSERT INTO `Country` VALUES (null, 'LS', 'Lesotho');
INSERT INTO `Country` VALUES (null, 'LR', 'Liberia');
INSERT INTO `Country` VALUES (null, 'LY', 'Libyan Arab Jamahiriya');
INSERT INTO `Country` VALUES (null, 'LI', 'Liechtenstein');
INSERT INTO `Country` VALUES (null, 'LT', 'Lithuania');
INSERT INTO `Country` VALUES (null, 'LU', 'Luxembourg');
INSERT INTO `Country` VALUES (null, 'MO', 'Macau');
INSERT INTO `Country` VALUES (null, 'MK', 'North Macedonia');
INSERT INTO `Country` VALUES (null, 'MG', 'Madagascar');
INSERT INTO `Country` VALUES (null, 'MW', 'Malawi');
INSERT INTO `Country` VALUES (null, 'MY', 'Malaysia');
INSERT INTO `Country` VALUES (null, 'MV', 'Maldives');
INSERT INTO `Country` VALUES (null, 'ML', 'Mali');
INSERT INTO `Country` VALUES (null, 'MT', 'Malta');
INSERT INTO `Country` VALUES (null, 'MH', 'Marshall Islands');
INSERT INTO `Country` VALUES (null, 'MQ', 'Martinique');
INSERT INTO `Country` VALUES (null, 'MR', 'Mauritania');
INSERT INTO `Country` VALUES (null, 'MU', 'Mauritius');
INSERT INTO `Country` VALUES (null, 'YT', 'Mayotte');
INSERT INTO `Country` VALUES (null, 'MX', 'Mexico');
INSERT INTO `Country` VALUES (null, 'FM', 'Micronesia, Federated States of');
INSERT INTO `Country` VALUES (null, 'MD', 'Moldova, Republic of');
INSERT INTO `Country` VALUES (null, 'MC', 'Monaco');
INSERT INTO `Country` VALUES (null, 'MN', 'Mongolia');
INSERT INTO `Country` VALUES (null, 'ME', 'Montenegro');
INSERT INTO `Country` VALUES (null, 'MS', 'Montserrat');
INSERT INTO `Country` VALUES (null, 'MA', 'Morocco');
INSERT INTO `Country` VALUES (null, 'MZ', 'Mozambique');
INSERT INTO `Country` VALUES (null, 'MM', 'Myanmar');
INSERT INTO `Country` VALUES (null, 'NA', 'Namibia');
INSERT INTO `Country` VALUES (null, 'NR', 'Nauru');
INSERT INTO `Country` VALUES (null, 'NP', 'Nepal');
INSERT INTO `Country` VALUES (null, 'NL', 'Netherlands');
INSERT INTO `Country` VALUES (null, 'AN', 'Netherlands Antilles');
INSERT INTO `Country` VALUES (null, 'NC', 'New Caledonia');
INSERT INTO `Country` VALUES (null, 'NZ', 'New Zealand');
INSERT INTO `Country` VALUES (null, 'NI', 'Nicaragua');
INSERT INTO `Country` VALUES (null, 'NE', 'Niger');
INSERT INTO `Country` VALUES (null, 'NG', 'Nigeria');
INSERT INTO `Country` VALUES (null, 'NU', 'Niue');
INSERT INTO `Country` VALUES (null, 'NF', 'Norfolk Island');
INSERT INTO `Country` VALUES (null, 'MP', 'Northern Mariana Islands');
INSERT INTO `Country` VALUES (null, 'NO', 'Norway');
INSERT INTO `Country` VALUES (null, 'OM', 'Oman');
INSERT INTO `Country` VALUES (null, 'PK', 'Pakistan');
INSERT INTO `Country` VALUES (null, 'PW', 'Palau');
INSERT INTO `Country` VALUES (null, 'PS', 'Palestine');
INSERT INTO `Country` VALUES (null, 'PA', 'Panama');
INSERT INTO `Country` VALUES (null, 'PG', 'Papua New Guinea');
INSERT INTO `Country` VALUES (null, 'PY', 'Paraguay');
INSERT INTO `Country` VALUES (null, 'PE', 'Peru');
INSERT INTO `Country` VALUES (null, 'PH', 'Philippines');
INSERT INTO `Country` VALUES (null, 'PN', 'Pitcairn');
INSERT INTO `Country` VALUES (null, 'PL', 'Poland');
INSERT INTO `Country` VALUES (null, 'PT', 'Portugal');
INSERT INTO `Country` VALUES (null, 'PR', 'Puerto Rico');
INSERT INTO `Country` VALUES (null, 'QA', 'Qatar');
INSERT INTO `Country` VALUES (null, 'RE', 'Reunion');
INSERT INTO `Country` VALUES (null, 'RO', 'Romania');
INSERT INTO `Country` VALUES (null, 'RU', 'Russian Federation');
INSERT INTO `Country` VALUES (null, 'RW', 'Rwanda');
INSERT INTO `Country` VALUES (null, 'KN', 'Saint Kitts and Nevis');
INSERT INTO `Country` VALUES (null, 'LC', 'Saint Lucia');
INSERT INTO `Country` VALUES (null, 'VC', 'Saint Vincent and the Grenadines');
INSERT INTO `Country` VALUES (null, 'WS', 'Samoa');
INSERT INTO `Country` VALUES (null, 'SM', 'San Marino');
INSERT INTO `Country` VALUES (null, 'ST', 'Sao Tome and Principe');
INSERT INTO `Country` VALUES (null, 'SA', 'Saudi Arabia');
INSERT INTO `Country` VALUES (null, 'SN', 'Senegal');
INSERT INTO `Country` VALUES (null, 'RS', 'Serbia');
INSERT INTO `Country` VALUES (null, 'SC', 'Seychelles');
INSERT INTO `Country` VALUES (null, 'SL', 'Sierra Leone');
INSERT INTO `Country` VALUES (null, 'SG', 'Singapore');
INSERT INTO `Country` VALUES (null, 'SK', 'Slovakia');
INSERT INTO `Country` VALUES (null, 'SI', 'Slovenia');
INSERT INTO `Country` VALUES (null, 'SB', 'Solomon Islands');
INSERT INTO `Country` VALUES (null, 'SO', 'Somalia');
INSERT INTO `Country` VALUES (null, 'ZA', 'South Africa');
INSERT INTO `Country` VALUES (null, 'GS', 'South Georgia South Sandwich Islands');
INSERT INTO `Country` VALUES (null, 'SS', 'South Sudan');
INSERT INTO `Country` VALUES (null, 'ES', 'Spain');
INSERT INTO `Country` VALUES (null, 'LK', 'Sri Lanka');
INSERT INTO `Country` VALUES (null, 'SH', 'St. Helena');
INSERT INTO `Country` VALUES (null, 'PM', 'St. Pierre and Miquelon');
INSERT INTO `Country` VALUES (null, 'SD', 'Sudan');
INSERT INTO `Country` VALUES (null, 'SR', 'Suriname');
INSERT INTO `Country` VALUES (null, 'SJ', 'Svalbard and Jan Mayen Islands');
INSERT INTO `Country` VALUES (null, 'SZ', 'Eswatini');
INSERT INTO `Country` VALUES (null, 'SE', 'Sweden');
INSERT INTO `Country` VALUES (null, 'CH', 'Switzerland');
INSERT INTO `Country` VALUES (null, 'SY', 'Syrian Arab Republic');
INSERT INTO `Country` VALUES (null, 'TW', 'Taiwan');
INSERT INTO `Country` VALUES (null, 'TJ', 'Tajikistan');
INSERT INTO `Country` VALUES (null, 'TZ', 'Tanzania, United Republic of');
INSERT INTO `Country` VALUES (null, 'TH', 'Thailand');
INSERT INTO `Country` VALUES (null, 'TG', 'Togo');
INSERT INTO `Country` VALUES (null, 'TK', 'Tokelau');
INSERT INTO `Country` VALUES (null, 'TO', 'Tonga');
INSERT INTO `Country` VALUES (null, 'TT', 'Trinidad and Tobago');
INSERT INTO `Country` VALUES (null, 'TN', 'Tunisia');
INSERT INTO `Country` VALUES (null, 'TR', 'Turkey');
INSERT INTO `Country` VALUES (null, 'TM', 'Turkmenistan');
INSERT INTO `Country` VALUES (null, 'TC', 'Turks and Caicos Islands');
INSERT INTO `Country` VALUES (null, 'TV', 'Tuvalu');
INSERT INTO `Country` VALUES (null, 'UG', 'Uganda');
INSERT INTO `Country` VALUES (null, 'UA', 'Ukraine');
INSERT INTO `Country` VALUES (null, 'AE', 'United Arab Emirates');
INSERT INTO `Country` VALUES (null, 'GB', 'United Kingdom');
INSERT INTO `Country` VALUES (null, 'US', 'United States');
INSERT INTO `Country` VALUES (null, 'UM', 'United States minor outlying islands');
INSERT INTO `Country` VALUES (null, 'UY', 'Uruguay');
INSERT INTO `Country` VALUES (null, 'UZ', 'Uzbekistan');
INSERT INTO `Country` VALUES (null, 'VU', 'Vanuatu');
INSERT INTO `Country` VALUES (null, 'VA', 'Vatican City State');
INSERT INTO `Country` VALUES (null, 'VE', 'Venezuela');
INSERT INTO `Country` VALUES (null, 'VN', 'Vietnam');
INSERT INTO `Country` VALUES (null, 'VG', 'Virgin Islands (British)');
INSERT INTO `Country` VALUES (null, 'VI', 'Virgin Islands (U.S.)');
INSERT INTO `Country` VALUES (null, 'WF', 'Wallis and Futuna Islands');
INSERT INTO `Country` VALUES (null, 'EH', 'Western Sahara');
INSERT INTO `Country` VALUES (null, 'YE', 'Yemen');
INSERT INTO `Country` VALUES (null, 'ZM', 'Zambia');
INSERT INTO `Country` VALUES (null, 'ZW', 'Zimbabwe');

-- Insert into Role
INSERT INTO Role (RoleName) VALUES 
('patient'), 
('doctor');

INSERT INTO `Specialization` VALUES (null, 'General Practitioner');
INSERT INTO `Specialization` VALUES (null, 'GP Telehealth');
INSERT INTO `Specialization` VALUES (null, 'Physiotherapist');
INSERT INTO `Specialization` VALUES (null, 'Dentist');
INSERT INTO `Specialization` VALUES (null, 'Psychologist');
INSERT INTO `Specialization` VALUES (null, 'Optometrist');
INSERT INTO `Specialization` VALUES (null, 'Chiropractor');
INSERT INTO `Specialization` VALUES (null, 'Podiatrist');
INSERT INTO `Specialization` VALUES (null, 'Opthalmologist');
INSERT INTO `Specialization` VALUES (null, 'Gynaecologist');
INSERT INTO `Specialization` VALUES (null, 'Pediatrician');
INSERT INTO `Specialization` VALUES (null, 'Psychiatrist');
INSERT INTO `Specialization` VALUES (null, 'Cardiologist');
INSERT INTO `Specialization` VALUES (null, 'Dermatologist');


INSERT INTO `Account` (`AccountID`, `EmailAddress`, `Password`, `VerificationStatus`, `CreatedDate`, `LastModifiedDate`, `RoleID`) VALUES
(1, 'test_patient@example.com', 'd6dffa63c5b7522a079276f6a1bc8323e099a94cfaa8180e89976af499dd0f6a', 'verified', '2024-05-29 04:44:00', '2024-05-29 04:44:00', 1),
(2, 'test_doctor@example.com', '9e682ddf058a006c441ed12f92fdcbd56ba8fdcd4e9a876f0a313e55dd77bcc9', 'verified', '2024-05-29 04:45:56', '2024-05-29 04:45:56', 2);

INSERT INTO `Patient` (`PatientID`, `FirstName`, `LastName`, `Gender`, `Address`, `ContactNumber`, `DateOfBirth`, `CountryID`, `EmergencyContactNumber`, `CreatedDate`, `LastModifiedDate`, `AccountID`) VALUES
(1, 'Patient', 'Test', 'male', '49 garfield', '0469078908', '2004-06-29', 18, '0469075026', '2024-05-29 04:44:00', '2024-05-29 04:44:00', 1);

INSERT INTO `Hospital` (`HospitalID`, `HospitalName`, `Address`, `Location`, `OfficeNumber`, `EmailAddress`, `OpeningTime`, `ClosingTime`, `AppointmentWindow`) VALUES
(1, 'Parramatta Medical Centre', '48 Smith Street', 'Parramatta', '0467899767', 'ofc@pmc.com', '09:00:00', '18:00:00', '00:15:00'),
(2, 'Granville Medical Centre', '34 harris street', 'Granville', '0457896589', 'ofc@gmc.com', '10:30:00', '19:00:00', '00:20:00');

INSERT INTO `Doctor` (`DoctorID`, `FirstName`, `LastName`, `ContactNumber`, `DateOfBirth`, `Gender`, `Description`, `SpecializationID`, `CreatedDate`, `LastModifiedDate`, `AccountID`, `HospitalID`) VALUES
(1, 'Doctor', 'Test', '0469078907', '1992-10-29', 'female', 'Dr. Emily Carter, MD, is a Board Certified General Practitioner specializing in Family Medicine. With a medical degree from Johns Hopkins University and residency training at Massachusetts General Hospital, she has extensive experience in primary care, having worked at Greenfield Family Clinic since 2010. Dr. Carter excels in diagnosing and treating a wide range of acute and chronic conditions, performing preventive care, and educating patients on health maintenance. Known for her empathetic communication and strong problem-solving skills, she is dedicated to holistic, patient-centered care. Dr. Carter is actively involved in community health initiatives and enjoys health and fitness, reading medical literature, and traveling.', 1, '2024-05-29 04:45:56', '2024-05-29 04:45:56', 2, 2);

