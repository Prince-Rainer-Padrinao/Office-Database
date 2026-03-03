<?php
$conn = mysqli_connect("localhost", "root", "", "peso_database");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Quick loop to escape apostrophes (e.g., O'Connor) so it doesn't break the SQL
    foreach($_POST as $key => $value) {
        if(!is_array($value)) {
            $_POST[$key] = mysqli_real_escape_string($conn, $value);
        }
    }

    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $sex = $_POST['sex'] ?? '';
    $dob = $_POST['dob'];
    $age = (int)$_POST['age'];
    $civil_status = $_POST['civil_status'] ?? '';
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $facebook = $_POST['facebook'];
    $ph_address = $_POST['ph_address'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    
    $passport_number = $_POST['passport_number'];
    $passport_expiry = $_POST['passport_expiry'];
    $oec_number = $_POST['oec_number'];
    $dmw_number = $_POST['dmw_number'];
    $sss_number = $_POST['sss_number'];
    $philhealth_number = $_POST['philhealth_number'];
    $pagibig_number = $_POST['pagibig_number'];
    
    $current_status = $_POST['current_status'] ?? '';
    $country_employment = $_POST['country_employment'];
    $job_position = $_POST['job_position'];
    $employer_name = $_POST['employer_name'];
    $employment_type = $_POST['employment_type'] ?? '';
    $deployment_date = $_POST['deployment_date'];
    $contract_duration = $_POST['contract_duration'];
    $monthly_salary = $_POST['monthly_salary'];
    
    $education = $_POST['education'] ?? '';
    $field_of_study = $_POST['field_of_study'];
    $tesda_cert = $_POST['tesda_cert'];
    $other_skills = $_POST['other_skills'];
    
    $kin_name = $_POST['kin_name'];
    $kin_relationship = $_POST['kin_relationship'];
    $kin_contact = $_POST['kin_contact'];
    $dependents = (int)$_POST['dependents'];
    
    $availed_assistance = $_POST['availed_assistance'] ?? '';
    $assistance_specify = $_POST['assistance_specify'];
    
    // Checkbox array handling! Combine all checked items with a comma
    $concerns = isset($_POST['concerns']) ? implode(", ", $_POST['concerns']) : '';
    $concerns_others = $_POST['concerns_others'];
    
    $emergency_contact_name = $_POST['emergency_contact_name'];
    $emergency_relationship = $_POST['emergency_relationship'];
    $emergency_contact_number = $_POST['emergency_contact_number'];
    
    $privacy_consent = $_POST['privacy_consent'] ?? '';
    $signature = $_POST['signature'];
    $date_signed = $_POST['date_signed'];

    $sql = "INSERT INTO ofw_profiles (
        last_name, first_name, middle_name, sex, dob, age, civil_status, contact_number, email, facebook, 
        ph_address, barangay, municipality, province, passport_number, passport_expiry, oec_number, dmw_number, 
        sss_number, philhealth_number, pagibig_number, current_status, country_employment, job_position, 
        employer_name, employment_type, deployment_date, contract_duration, monthly_salary, education, 
        field_of_study, tesda_cert, other_skills, kin_name, kin_relationship, kin_contact, dependents, 
        availed_assistance, assistance_specify, concerns, concerns_others, emergency_contact_name, 
        emergency_relationship, emergency_contact_number, privacy_consent, signature, date_signed
    ) VALUES (
        '$last_name', '$first_name', '$middle_name', '$sex', '$dob', $age, '$civil_status', '$contact_number', '$email', '$facebook', 
        '$ph_address', '$barangay', '$municipality', '$province', '$passport_number', '$passport_expiry', '$oec_number', '$dmw_number', 
        '$sss_number', '$philhealth_number', '$pagibig_number', '$current_status', '$country_employment', '$job_position', 
        '$employer_name', '$employment_type', '$deployment_date', '$contract_duration', '$monthly_salary', '$education', 
        '$field_of_study', '$tesda_cert', '$other_skills', '$kin_name', '$kin_relationship', '$kin_contact', $dependents, 
        '$availed_assistance', '$assistance_specify', '$concerns', '$concerns_others', '$emergency_contact_name', 
        '$emergency_relationship', '$emergency_contact_number', '$privacy_consent', '$signature', '$date_signed'
    )";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($conn);
    }
}
?>