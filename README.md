## Getting Started

Follow the steps below to set up and run the application.

### Prerequisites

- Check if http://localhost is working.
- If it is working, then your own computer already has a default web server installed, you may have to disable it first, or just change its current port from the 80 to something else such as 8000 (unless you have already installed something at that port).
- You can use the command apachectl stop to turn off the built-in web server on a Mac or Linux, or go to the Control Panel to turn it off on Windows.
Ensure you have the following installed on your machine:
- Download **XAMPP**, which includes MySQL: [Download XAMPP](https://www.apachefriends.org/index.html).
- Run the XAMPP installer to install on the default c:\xampp (for Windows) or Applications/XAMPP (for Macs).
- XAMPP Control Panel will be available on the Desktop.
- c:\xampp is created, and the default website root is at c:\xampp\htdocs
- Enable Apache and MySQL services (they will appear in the list of Services). 
- Start Apache and MySQL.

### Setup Instructions

1. **Clone the Repository**

   Clone this repository to your local machine using:

   ```bash
   git clone https://github.com/chandana89/doc-app.git
   ```
2. **Setup htdocs**
   Replace c:\xampp\htdocs with the htdocs folder which is cloned with the repository.
   
2. **MySQL database configuration**
   - Browse http://localhost/phpmyadmin/
   - Create a new database named *medi_connect* or with any other name if you want.
   - Open mediConnect.sql. Before executing the script, if you named the database differently then change the database name *medi_connect* to the database name created by you in the below lines of the script. 
      
      ```bash
      CREATE DATABASE IF NOT EXISTS medi_connect; 
      USE medi_connect; 
      ```
  - Copy and paste the script on to the SQL tab of the newly created database and click on GO. 
  - Add database username, password and database name to configDB.php file in c:\xampp\htdocs\config folder.
    
     ```bash
        define("DB_USER","root");
	      define("DB_PASS","");
	      define("DB_NAME","medi_connect");
      ```
  
### Access the Application

To access the application, open [Access Application](http://localhost) in your local browser after running it.

### User Details

**User Details of Patient:**
Username: test_patient@example.com
Password: Testpatient@1234

**User Details of Doctor:**
Username: test_doctor@example.com
Password: Testdoctor@1234

---

By following these steps, you should have the application up and running on your local machine. If you have any questions or need further assistance, please feel free to contact the repository owner.
