user link
192.168.1.105/PESOoffice_db (depends on IPv4 Address of the host/admin)

admin link
http://localhost/PESOoffice_db/index.php

database link
http://localhost/phpmyadmin/index.php
http://localhost/phpmyadmin/index.php?route=/database/structure&db=peso_database

🖥 NEW PC SETUP GUIDE (XAMPP + PESO Database) 1️⃣ Install XAMPP

Download and install XAMPP

Open XAMPP Control Panel

Start:

✅ Apache

✅ MySQL

2️⃣ Download Project Files

Open Google Chrome

Go to the GitHub repository: https://github.com/Prince-Rainer-Padrinao/Office-Database

Download these files:

index.php insert.php database.php edit_status.php

3️⃣ Setup Project Folder

Go to:

C:\xampp\htdocs

Create a new folder:

PESOoffice_db

Paste the 4 PHP files inside that folder.

4️⃣ Create the Database

Open browser:

http://localhost/phpmyadmin

Click New

Database name: peso_database

Click Create

Open the SQL tab:

DROP TABLE IF EXISTS employees;

CREATE TABLE employees ( id INT AUTO_INCREMENT PRIMARY KEY, last_name VARCHAR(100), first_name VARCHAR(100), middle_name VARCHAR(100), sex VARCHAR(20), dob DATE, age INT, civil_status VARCHAR(50), contact_number VARCHAR(50), email VARCHAR(100), facebook VARCHAR(100), ph_address TEXT, barangay VARCHAR(100), municipality VARCHAR(100), province VARCHAR(100), passport_number VARCHAR(100), passport_expiry DATE, oec_number VARCHAR(100), dmw_number VARCHAR(100), sss_number VARCHAR(100), philhealth_number VARCHAR(100), pagibig_number VARCHAR(100), current_status VARCHAR(100), country_employment VARCHAR(100), job_position VARCHAR(100), employer_name VARCHAR(150), employment_type VARCHAR(50), deployment_date DATE, contract_duration VARCHAR(100), monthly_salary VARCHAR(100), education VARCHAR(100), field_of_study VARCHAR(100), tesda_cert VARCHAR(100), other_skills TEXT, kin_name VARCHAR(150), kin_relationship VARCHAR(100), kin_contact VARCHAR(50), dependents INT, availed_assistance VARCHAR(10), assistance_specify TEXT, concerns TEXT, concerns_others TEXT, emergency_contact_name VARCHAR(150), emergency_relationship VARCHAR(100), emergency_contact_number VARCHAR(50), privacy_consent VARCHAR(20), signature VARCHAR(150), date_signed DATE, status VARCHAR(20) NOT NULL DEFAULT 'Active' );

Click Go

5️⃣ Test the System

Open browser and go to:

http://localhost/PESOoffice_db/index.php If it loads, test inserting data. To check saved data:

http://localhost/phpmyadmin

→ peso_database → ofw_profiles → Browse

=================================================

*REALTIME Local backup setup: 🔄 Local Automatic Backup Setup 6️⃣ Setup Backup Script

Download xampp_backup.bat from GitHub

Place it inside:

C:\xampp\htdocs\PESOoffice_db 7️⃣ Schedule Automatic Backup

Open Windows Task Scheduler

Click Create Basic Task

Name:

XAMPP_DB_Backup

Trigger:

Daily (or as preferred)

Action:

Start a Program

Browse and select:

C:\xampp\htdocs\PESOoffice_db\xampp_backup.bat

Finish setup.

TO DO:
google forms integration
load SQL file (backup)


LIMITATIONS:
Must be connected to same network (same wi-fi)

RECOMMENDATIONS:
Add or buy a domain to have an online server
Add a Cloud backup
Add a function to change password for admin and user
Allow google or gmail sign in

===========================================================

OLD INSTRUCTIONS ***************************

Open XAMPP software
click start for Apache and MySQL

USE MAIN PC - HOST PC

1. click wifi icon on bottom right corner of the screen
2. click properties icon
3. scroll down and find "IPv4 address" 
4. Copy the address number (example: 192.168.1.105)
5. Type the URL in this format:

192.168.1.105/PESOoffice_db 
(example only, IPv4 address may change)

===========================================================

user link
192.168.1.105/PESOoffice_db (depends on IPv4 Address of the host/admin)

admin link
http://localhost/PESOoffice_db/index.php

database link
http://localhost/phpmyadmin/index.php
http://localhost/phpmyadmin/index.php?route=/database/structure&db=peso_database


copy to phpadmin - sql tab then hit go
add peso_database

CREATE TABLE ofw_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100), first_name VARCHAR(100), middle_name VARCHAR(100),
    sex VARCHAR(20), dob DATE, age INT, civil_status VARCHAR(50),
    contact_number VARCHAR(50), email VARCHAR(100), facebook VARCHAR(100),
    ph_address TEXT, barangay VARCHAR(100), municipality VARCHAR(100), province VARCHAR(100),
    passport_number VARCHAR(50), passport_expiry DATE, oec_number VARCHAR(50),
    dmw_number VARCHAR(50), sss_number VARCHAR(50), philhealth_number VARCHAR(50), pagibig_number VARCHAR(50),
    current_status VARCHAR(100), country_employment VARCHAR(100), job_position VARCHAR(100),
    employer_name VARCHAR(255), employment_type VARCHAR(50), deployment_date DATE,
    contract_duration VARCHAR(100), monthly_salary VARCHAR(100),
    education VARCHAR(100), field_of_study VARCHAR(100), tesda_cert VARCHAR(100), other_skills TEXT,
    kin_name VARCHAR(100), kin_relationship VARCHAR(50), kin_contact VARCHAR(50), dependents INT,
    availed_assistance VARCHAR(10), assistance_specify TEXT, concerns TEXT, concerns_others TEXT,
    emergency_contact_name VARCHAR(100), emergency_relationship VARCHAR(50), emergency_contact_number VARCHAR(50),
    privacy_consent VARCHAR(20), signature VARCHAR(100), date_signed DATE
);
