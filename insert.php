<?php
$host = "localhost";
$user = "root"; 
$pass = "";     
$dbname = "peso_database"; // Updated database name!

$conn = mysqli_connect($host, $user, $pass, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_name = $_POST['emp_name'];
    $emp_code = $_POST['emp_code'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $department = $_POST['department'];
    $location = $_POST['location'];
    
    $basic_salary = $_POST['basic_salary'];
    $hra = $_POST['hra'];
    $medical = $_POST['medical'];
    $conveyance = $_POST['conveyance'];
    $deductions = $_POST['deductions'];
    $net_pay = $_POST['net_pay'];

    $sql = "INSERT INTO employees (emp_name, emp_code, email, dob, department, location, basic_salary, hra, medical, conveyance, deductions, net_pay) 
            VALUES ('$emp_name', '$emp_code', '$email', '$dob', '$department', '$location', '$basic_salary', '$hra', '$medical', '$conveyance', '$deductions', '$net_pay')";

    if (mysqli_query($conn, $sql)) {
        echo "success"; // We just send back this one word
    } else {
        echo "error"; 
    }
}
?>