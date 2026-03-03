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
