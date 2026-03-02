Open XAMPP software
click start for Apache and MySQL

user link
192.168.1.105/PESOoffice_db

admin link
http://localhost/PESOoffice_db/index.php

database link
http://localhost/phpmyadmin/index.php
http://localhost/phpmyadmin/index.php?route=/database/structure&db=peso_database


copy to phpadmin - sql tab then hit go

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_name VARCHAR(100),
    emp_code VARCHAR(50),
    email VARCHAR(100),
    dob VARCHAR(50),
    department VARCHAR(50),
    location VARCHAR(50),
    basic_salary DECIMAL(10,2),
    hra DECIMAL(10,2),
    medical DECIMAL(10,2),
    conveyance DECIMAL(10,2),
    deductions DECIMAL(10,2),
    net_pay DECIMAL(10,2),
    status VARCHAR(20) NOT NULL DEFAULT 'Active'
);
