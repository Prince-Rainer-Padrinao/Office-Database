<?php
// Security check: Only allow this script to be run from the host computer itself (localhost)
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    die("❌ Access Denied. This script can only be run locally.");
}

$conn = mysqli_connect("localhost", "root", "", "peso_database");
if (!$conn) die("Database connection failed.");

$file_path = "import_data.csv";

if (!file_exists($file_path)) {
    die("❌ Error: Could not find the CSV file in the XAMPP folder.");
}

$handle = fopen($file_path, "r");
fgetcsv($handle); // Skip Google Sheets header row

$added = 0;
$skipped = 0;

while($data = fgetcsv($handle)) {
    if(count($data) < 47) continue; // Skip empty rows

    $clean = function($str) use ($conn) { return mysqli_real_escape_string($conn, $str ?? ''); };

    // The mapping
    $last_name = $clean($data[1]);
    $first_name = $clean($data[2]);
    $middle_name = $clean($data[3]);
    $sex = $clean($data[4]);
    $dob = $clean($data[5]);
    $age = (int)$clean($data[6]);
    $civil_status = $clean($data[7]);
    $contact_number = $clean($data[8]);
    $email = $clean($data[9]);
    $facebook = $clean($data[10]);
    $ph_address = $clean($data[11]);
    $barangay = $clean($data[12]);
    $municipality = $clean($data[13]);
    $province = $clean($data[14]);
    
    // The Passport
    $passport_number = $clean($data[15]);
    
    // Check for Duplicates
    $check = mysqli_query($conn, "SELECT id FROM ofw_profiles WHERE passport_number = '$passport_number'");
    if (mysqli_num_rows($check) > 0) {
        $skipped++; 
        continue;
    }

    $passport_expiry = $clean($data[16]);
    $oec_number = $clean($data[17]);
    $dmw_number = $clean($data[18]);
    $sss_number = $clean($data[19]);
    $philhealth_number = $clean($data[20]);
    $pagibig_number = $clean($data[21]);

    $current_status = $clean($data[22]);
    $country_employment = $clean($data[23]);
    $job_position = $clean($data[24]);
    $employer_name = $clean($data[25]);
    $employment_type = $clean($data[26]);
    $deployment_date = $clean($data[27]);
    $contract_duration = $clean($data[28]);
    $monthly_salary = $clean($data[29]);

    $education = $clean($data[30]);
    $field_of_study = $clean($data[31]);
    $tesda_cert = $clean($data[32]);
    $other_skills = $clean($data[33]);

    $kin_name = $clean($data[34]);
    $kin_relationship = $clean($data[35]);
    $kin_contact = $clean($data[36]);
    $dependents = (int)$clean($data[37]);

    $availed_assistance = $clean($data[38]);
    $assistance_specify = $clean($data[39]);
    $concerns = $clean($data[40]);
    $concerns_others = $clean($data[41]);

    $emergency_contact_name = $clean($data[42]);
    $emergency_relationship = $clean($data[43]);
    $emergency_contact_number = $clean($data[44]);

    $privacy_consent = $clean($data[45]);
    $signature = $clean($data[46]);
    $date_signed = $clean($data[47]);

    $sql = "INSERT INTO ofw_profiles (
        last_name, first_name, middle_name, sex, dob, age, civil_status, contact_number, email, facebook, ph_address, barangay, municipality, province, 
        passport_number, passport_expiry, oec_number, dmw_number, sss_number, philhealth_number, pagibig_number, 
        current_status, country_employment, job_position, employer_name, employment_type, deployment_date, contract_duration, monthly_salary, 
        education, field_of_study, tesda_cert, other_skills, 
        kin_name, kin_relationship, kin_contact, dependents, 
        availed_assistance, assistance_specify, concerns, concerns_others, 
        emergency_contact_name, emergency_relationship, emergency_contact_number, 
        privacy_consent, signature, date_signed
    ) VALUES (
        '$last_name', '$first_name', '$middle_name', '$sex', '$dob', $age, '$civil_status', '$contact_number', '$email', '$facebook', '$ph_address', '$barangay', '$municipality', '$province', 
        '$passport_number', '$passport_expiry', '$oec_number', '$dmw_number', '$sss_number', '$philhealth_number', '$pagibig_number', 
        '$current_status', '$country_employment', '$job_position', '$employer_name', '$employment_type', '$deployment_date', '$contract_duration', '$monthly_salary', 
        '$education', '$field_of_study', '$tesda_cert', '$other_skills', 
        '$kin_name', '$kin_relationship', '$kin_contact', $dependents, 
        '$availed_assistance', '$assistance_specify', '$concerns', '$concerns_others', 
        '$emergency_contact_name', '$emergency_relationship', '$emergency_contact_number', 
        '$privacy_consent', '$signature', '$date_signed'
    )";
    mysqli_query($conn, $sql);
    $added++;
}
fclose($handle);

// Delete the CSV file so it doesn't leave junk on the server
unlink($file_path);

echo "\n=======================================\n";
echo "       DATABASE SYNC COMPLETE!         \n";
echo "=======================================\n";
echo "  [+] NEW PROFILES ADDED : $added\n";
echo "  [-] DUPLICATES SKIPPED : $skipped\n";
echo "=======================================\n\n";
?>