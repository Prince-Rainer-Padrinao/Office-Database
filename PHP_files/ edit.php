<?php
session_start();

// Security: Kick out anyone who isn't logged in as Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<h2>Access Denied. Admins only.</h2><a href='database.php'>Go Back</a>";
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "peso_database");

// --- HANDLE THE UPDATE WHEN ADMIN HITS SUBMIT ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $clean = function($data) use ($conn) { return mysqli_real_escape_string($conn, $data ?? ''); };

    // I. PERSONAL INFO
    $last_name = $clean($_POST['last_name']);
    $first_name = $clean($_POST['first_name']);
    $middle_name = $clean($_POST['middle_name']);
    $sex = $clean($_POST['sex']);
    $dob = $clean($_POST['dob']);
    $age = (int)$clean($_POST['age']);
    $civil_status = $clean($_POST['civil_status']);
    $contact_number = $clean($_POST['contact_number']);
    $email = $clean($_POST['email']);
    $facebook = $clean($_POST['facebook']);
    $ph_address = $clean($_POST['ph_address']);
    $barangay = $clean($_POST['barangay']);
    $municipality = $clean($_POST['municipality']);
    $province = $clean($_POST['province']);

    // II. PASSPORT & ID
    $passport_number = $clean($_POST['passport_number']);
    $passport_expiry = $clean($_POST['passport_expiry']);
    $oec_number = $clean($_POST['oec_number']);
    $dmw_number = $clean($_POST['dmw_number']);
    $sss_number = $clean($_POST['sss_number']);
    $philhealth_number = $clean($_POST['philhealth_number']);
    $pagibig_number = $clean($_POST['pagibig_number']);

    // III. EMPLOYMENT
    $current_status = $clean($_POST['current_status']);
    $country_employment = $clean($_POST['country_employment']);
    $job_position = $clean($_POST['job_position']);
    $employer_name = $clean($_POST['employer_name']);
    $employment_type = $clean($_POST['employment_type']);
    $deployment_date = $clean($_POST['deployment_date']);
    $contract_duration = $clean($_POST['contract_duration']);
    $monthly_salary = $clean($_POST['monthly_salary']);

    // IV. SKILLS & TRAINING
    $education = $clean($_POST['education']);
    $field_of_study = $clean($_POST['field_of_study']);
    $tesda_cert = $clean($_POST['tesda_cert']);
    $other_skills = $clean($_POST['other_skills']);

    // V. FAMILY INFO
    $kin_name = $clean($_POST['kin_name']);
    $kin_relationship = $clean($_POST['kin_relationship']);
    $kin_contact = $clean($_POST['kin_contact']);
    $dependents = (int)$clean($_POST['dependents']);

    // VI. ASSISTANCE & CONCERNS
    $availed_assistance = $clean($_POST['availed_assistance']);
    $assistance_specify = $clean($_POST['assistance_specify']);
    $concerns_raw = isset($_POST['concerns']) ? implode(", ", $_POST['concerns']) : '';
    $concerns = $clean($concerns_raw);
    $concerns_others = $clean($_POST['concerns_others']);

    // VII & VIII. EMERGENCY & PRIVACY
    $emergency_contact_name = $clean($_POST['emergency_contact_name']);
    $emergency_relationship = $clean($_POST['emergency_relationship']);
    $emergency_contact_number = $clean($_POST['emergency_contact_number']);
    $privacy_consent = $clean($_POST['privacy_consent']);
    $signature = $clean($_POST['signature']);
    $date_signed = $clean($_POST['date_signed']);

    // The Massive Update Query
    $sql = "UPDATE ofw_profiles SET 
        last_name='$last_name', first_name='$first_name', middle_name='$middle_name', sex='$sex', dob='$dob', age=$age, civil_status='$civil_status', contact_number='$contact_number', email='$email', facebook='$facebook', ph_address='$ph_address', barangay='$barangay', municipality='$municipality', province='$province', 
        passport_number='$passport_number', passport_expiry='$passport_expiry', oec_number='$oec_number', dmw_number='$dmw_number', sss_number='$sss_number', philhealth_number='$philhealth_number', pagibig_number='$pagibig_number', 
        current_status='$current_status', country_employment='$country_employment', job_position='$job_position', employer_name='$employer_name', employment_type='$employment_type', deployment_date='$deployment_date', contract_duration='$contract_duration', monthly_salary='$monthly_salary', 
        education='$education', field_of_study='$field_of_study', tesda_cert='$tesda_cert', other_skills='$other_skills', 
        kin_name='$kin_name', kin_relationship='$kin_relationship', kin_contact='$kin_contact', dependents=$dependents, 
        availed_assistance='$availed_assistance', assistance_specify='$assistance_specify', concerns='$concerns', concerns_others='$concerns_others', 
        emergency_contact_name='$emergency_contact_name', emergency_relationship='$emergency_relationship', emergency_contact_number='$emergency_contact_number', 
        privacy_consent='$privacy_consent', signature='$signature', date_signed='$date_signed'
        WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Full profile updated successfully!'); window.location.href='database.php';</script>";
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// --- FETCH THE EXISTING RECORD TO FILL THE FORM ---
if (!isset($_GET['id'])) {
    header("Location: database.php");
    exit;
}

$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM ofw_profiles WHERE id = $id");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "Record not found.";
    exit;
}

// Helper arrays to make checking checkboxes easier
$saved_concerns = explode(", ", $row['concerns']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Full OFW Profile</title>
    <style>
        body { font-family: Calibri, Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 40px 20px; }
        .form-container { background-color: white; border: 1px solid #000; max-width: 900px; margin: 0 auto; box-shadow: 2px 2px 15px rgba(0,0,0,0.2); padding: 0; }
        .form-header { background-color: #ffca28; color: black; padding: 20px; font-size: 28px; font-weight: bold; text-align: center; text-transform: uppercase; border-bottom: 2px solid #000; }
        .form-body { padding: 30px 50px; }
        .section-title { font-size: 18px; font-weight: bold; color: #3b5336; border-bottom: 2px solid #3b5336; margin-top: 30px; margin-bottom: 15px; padding-bottom: 5px; }
        .form-row { display: flex; gap: 20px; margin-bottom: 15px; flex-wrap: wrap; }
        .form-group { flex: 1; display: flex; flex-direction: column; min-width: 200px; }
        .form-group.full-width { flex: 100%; }
        .form-group label { font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="number"], .form-group input[type="date"] { padding: 8px; border: 1px solid #000; background-color: #f9f9f9; font-family: Calibri, Arial, sans-serif; font-size: 15px; outline: none; width: 100%; box-sizing: border-box; }
        .radio-group, .checkbox-group { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; padding: 5px 0; }
        .radio-group label, .checkbox-group label { font-weight: normal; display: flex; align-items: center; gap: 5px; cursor: pointer; }
        .submit-container { text-align: center; padding: 30px 0; margin-top: 20px; background-color: #eef2ed; border-top: 1px solid #ccc; }
        .submit-btn { padding: 12px 50px; font-weight: bold; font-size: 18px; border: 1px solid #000; background-color: #3b5336; color: white; cursor: pointer; text-transform: uppercase; }
        .cancel-btn { padding: 12px 50px; font-weight: bold; font-size: 18px; border: 1px solid #000; background-color: #ccc; color: black; cursor: pointer; text-transform: uppercase; text-decoration: none; margin-left: 10px; }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        ✏️ Edit Profile: <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
    </div>
    
    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        
        <div class="form-body">
            <div class="section-title">I. PERSONAL INFORMATION</div>
            <div class="form-row">
                <div class="form-group"><label>Last Name</label><input type="text" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>" required></div>
                <div class="form-group"><label>First Name</label><input type="text" name="first_name" value="<?php echo htmlspecialchars($row['first_name']); ?>" required></div>
                <div class="form-group"><label>Middle Name</label><input type="text" name="middle_name" value="<?php echo htmlspecialchars($row['middle_name']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group" style="flex: 2;">
                    <label>Sex</label>
                    <div class="radio-group">
                        <label><input type="radio" name="sex" value="Male" <?php if($row['sex'] == 'Male') echo 'checked'; ?> required> Male</label>
                        <label><input type="radio" name="sex" value="Female" <?php if($row['sex'] == 'Female') echo 'checked'; ?>> Female</label>
                        <label><input type="radio" name="sex" value="Prefer not to say" <?php if($row['sex'] == 'Prefer not to say') echo 'checked'; ?>> Prefer not to say</label>
                    </div>
                </div>
                <div class="form-group"><label>Date of Birth</label><input type="date" name="dob" value="<?php echo htmlspecialchars($row['dob']); ?>" required></div>
                <div class="form-group"><label>Age</label><input type="number" name="age" value="<?php echo htmlspecialchars($row['age']); ?>" required></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Civil Status</label>
                    <div class="radio-group">
                        <label><input type="radio" name="civil_status" value="Single" <?php if($row['civil_status'] == 'Single') echo 'checked'; ?> required> Single</label>
                        <label><input type="radio" name="civil_status" value="Married" <?php if($row['civil_status'] == 'Married') echo 'checked'; ?>> Married</label>
                        <label><input type="radio" name="civil_status" value="Widowed" <?php if($row['civil_status'] == 'Widowed') echo 'checked'; ?>> Widowed</label>
                        <label><input type="radio" name="civil_status" value="Separated" <?php if($row['civil_status'] == 'Separated') echo 'checked'; ?>> Separated</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contact Number</label><input type="text" name="contact_number" value="<?php echo htmlspecialchars($row['contact_number']); ?>" required></div>
                <div class="form-group"><label>Email Address</label><input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"></div>
                <div class="form-group"><label>Facebook Account (optional)</label><input type="text" name="facebook" value="<?php echo htmlspecialchars($row['facebook']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width"><label>Complete Philippine Address (Street/Block/Lot)</label><input type="text" name="ph_address" value="<?php echo htmlspecialchars($row['ph_address']); ?>" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Barangay</label><input type="text" name="barangay" value="<?php echo htmlspecialchars($row['barangay']); ?>" required></div>
                <div class="form-group"><label>Municipality</label><input type="text" name="municipality" value="<?php echo htmlspecialchars($row['municipality']); ?>" required></div>
                <div class="form-group"><label>Province</label><input type="text" name="province" value="<?php echo htmlspecialchars($row['province']); ?>" required></div>
            </div>

            <div class="section-title">II. PASSPORT & IDENTIFICATION DETAILS</div>
            <div class="form-row">
                <div class="form-group"><label>Passport Number</label><input type="text" name="passport_number" value="<?php echo htmlspecialchars($row['passport_number']); ?>" required></div>
                <div class="form-group"><label>Passport Expiry Date</label><input type="date" name="passport_expiry" value="<?php echo htmlspecialchars($row['passport_expiry']); ?>" required></div>
                <div class="form-group"><label>OEC Number (if available)</label><input type="text" name="oec_number" value="<?php echo htmlspecialchars($row['oec_number']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>DMW/POEA E-Registration Number</label><input type="text" name="dmw_number" value="<?php echo htmlspecialchars($row['dmw_number']); ?>"></div>
                <div class="form-group"><label>SSS Number</label><input type="text" name="sss_number" value="<?php echo htmlspecialchars($row['sss_number']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>PhilHealth Number</label><input type="text" name="philhealth_number" value="<?php echo htmlspecialchars($row['philhealth_number']); ?>"></div>
                <div class="form-group"><label>Pag-IBIG Number</label><input type="text" name="pagibig_number" value="<?php echo htmlspecialchars($row['pagibig_number']); ?>"></div>
            </div>

            <div class="section-title">III. EMPLOYMENT DETAILS (ABROAD)</div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Current Status</label>
                    <div class="radio-group">
                        <label><input type="radio" name="current_status" value="Active OFW" <?php if($row['current_status'] == 'Active OFW') echo 'checked'; ?> required> Active OFW</label>
                        <label><input type="radio" name="current_status" value="Vacationing in PH" <?php if($row['current_status'] == 'Vacationing in PH') echo 'checked'; ?>> Vacationing in PH</label>
                        <label><input type="radio" name="current_status" value="End of Contract" <?php if($row['current_status'] == 'End of Contract') echo 'checked'; ?>> End of Contract</label>
                        <label><input type="radio" name="current_status" value="Repatriated" <?php if($row['current_status'] == 'Repatriated') echo 'checked'; ?>> Repatriated</label>
                        <label><input type="radio" name="current_status" value="Returning to Host Country" <?php if($row['current_status'] == 'Returning to Host Country') echo 'checked'; ?>> Returning to Host Country</label>
                        <label><input type="radio" name="current_status" value="Jobseeker" <?php if($row['current_status'] == 'Jobseeker') echo 'checked'; ?>> Jobseeker</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Country of Employment</label><input type="text" name="country_employment" value="<?php echo htmlspecialchars($row['country_employment']); ?>" required></div>
                <div class="form-group"><label>Job Position/Occupation</label><input type="text" name="job_position" value="<?php echo htmlspecialchars($row['job_position']); ?>" required></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width"><label>Employer/Company Name</label><input type="text" name="employer_name" value="<?php echo htmlspecialchars($row['employer_name']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Employment Type</label>
                    <div class="radio-group">
                        <label><input type="radio" name="employment_type" value="Land-based" <?php if($row['employment_type'] == 'Land-based') echo 'checked'; ?> required> Land-based</label>
                        <label><input type="radio" name="employment_type" value="Sea-based" <?php if($row['employment_type'] == 'Sea-based') echo 'checked'; ?>> Sea-based</label>
                    </div>
                </div>
                <div class="form-group"><label>Deployment Date</label><input type="date" name="deployment_date" value="<?php echo htmlspecialchars($row['deployment_date']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contract Duration</label><input type="text" name="contract_duration" value="<?php echo htmlspecialchars($row['contract_duration']); ?>"></div>
                <div class="form-group"><label>Monthly Salary (currency)</label><input type="text" name="monthly_salary" value="<?php echo htmlspecialchars($row['monthly_salary']); ?>"></div>
            </div>

            <div class="section-title">IV. SKILLS & TRAINING</div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Highest Educational Attainment</label>
                    <div class="radio-group">
                        <label><input type="radio" name="education" value="Elementary" <?php if($row['education'] == 'Elementary') echo 'checked'; ?> required> Elementary</label>
                        <label><input type="radio" name="education" value="High School" <?php if($row['education'] == 'High School') echo 'checked'; ?>> High School</label>
                        <label><input type="radio" name="education" value="Vocational" <?php if($row['education'] == 'Vocational') echo 'checked'; ?>> Vocational</label>
                        <label><input type="radio" name="education" value="College" <?php if($row['education'] == 'College') echo 'checked'; ?>> College</label>
                        <label><input type="radio" name="education" value="Postgraduate" <?php if($row['education'] == 'Postgraduate') echo 'checked'; ?>> Postgraduate</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Field of Study</label><input type="text" name="field_of_study" value="<?php echo htmlspecialchars($row['field_of_study']); ?>"></div>
                <div class="form-group"><label>TESDA Certification (if any)</label><input type="text" name="tesda_cert" value="<?php echo htmlspecialchars($row['tesda_cert']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width"><label>Other Skills/Certifications</label><input type="text" name="other_skills" value="<?php echo htmlspecialchars($row['other_skills']); ?>"></div>
            </div>

            <div class="section-title">V. FAMILY INFORMATION</div>
            <div class="form-row">
                <div class="form-group"><label>Name of Beneficiary/Next of Kin</label><input type="text" name="kin_name" value="<?php echo htmlspecialchars($row['kin_name']); ?>" required></div>
                <div class="form-group"><label>Relationship</label><input type="text" name="kin_relationship" value="<?php echo htmlspecialchars($row['kin_relationship']); ?>" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contact Number</label><input type="text" name="kin_contact" value="<?php echo htmlspecialchars($row['kin_contact']); ?>" required></div>
                <div class="form-group"><label>Number of Dependents</label><input type="number" name="dependents" value="<?php echo htmlspecialchars($row['dependents']); ?>"></div>
            </div>

            <div class="section-title">VI. ASSISTANCE & CONCERNS</div>
            <div class="form-row">
                <div class="form-group">
                    <label>Have you availed any DMW/OWWA assistance?</label>
                    <div class="radio-group">
                        <label><input type="radio" name="availed_assistance" value="Yes" <?php if($row['availed_assistance'] == 'Yes') echo 'checked'; ?> required> Yes</label>
                        <label><input type="radio" name="availed_assistance" value="No" <?php if($row['availed_assistance'] == 'No') echo 'checked'; ?>> No</label>
                    </div>
                </div>
                <div class="form-group"><label>If yes, specify:</label><input type="text" name="assistance_specify" value="<?php echo htmlspecialchars($row['assistance_specify']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Current Concerns/Needs</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="concerns[]" value="Legal Assistance" <?php if(in_array('Legal Assistance', $saved_concerns)) echo 'checked'; ?>> Legal Assistance</label>
                        <label><input type="checkbox" name="concerns[]" value="Repatriation" <?php if(in_array('Repatriation', $saved_concerns)) echo 'checked'; ?>> Repatriation</label>
                        <label><input type="checkbox" name="concerns[]" value="Financial Assistance" <?php if(in_array('Financial Assistance', $saved_concerns)) echo 'checked'; ?>> Financial Assistance</label>
                        <label><input type="checkbox" name="concerns[]" value="Livelihood Program" <?php if(in_array('Livelihood Program', $saved_concerns)) echo 'checked'; ?>> Livelihood Program</label>
                        <label><input type="checkbox" name="concerns[]" value="Scholarship" <?php if(in_array('Scholarship', $saved_concerns)) echo 'checked'; ?>> Scholarship</label>
                        <label><input type="checkbox" name="concerns[]" value="Reintegration Program" <?php if(in_array('Reintegration Program', $saved_concerns)) echo 'checked'; ?>> Reintegration Program</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full-width"><label>Others (specify):</label><input type="text" name="concerns_others" value="<?php echo htmlspecialchars($row['concerns_others']); ?>"></div>
            </div>

            <div class="section-title">VII. EMERGENCY INFORMATION</div>
            <div class="form-row">
                <div class="form-group"><label>Emergency Contact Person</label><input type="text" name="emergency_contact_name" value="<?php echo htmlspecialchars($row['emergency_contact_name']); ?>" required></div>
                <div class="form-group"><label>Relationship</label><input type="text" name="emergency_relationship" value="<?php echo htmlspecialchars($row['emergency_relationship']); ?>" required></div>
                <div class="form-group"><label>Contact Number</label><input type="text" name="emergency_contact_number" value="<?php echo htmlspecialchars($row['emergency_contact_number']); ?>" required></div>
            </div>

            <div class="section-title">VIII. DATA PRIVACY CONSENT</div>
            <div class="form-row">
                <div class="form-group">
                    <label style="color: #c00;">
                        <input type="checkbox" name="privacy_consent" value="Agreed" <?php if($row['privacy_consent'] == 'Agreed') echo 'checked'; ?> required> I agree and consent to the Data Privacy terms above.
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>E-Signature (Type Full Name)</label><input type="text" name="signature" value="<?php echo htmlspecialchars($row['signature']); ?>" required></div>
                <div class="form-group"><label>Date Signed</label><input type="date" name="date_signed" value="<?php echo htmlspecialchars($row['date_signed']); ?>" required></div>
            </div>

        </div>

        <div class="submit-container">
            <button type="submit" class="submit-btn">Save Full Profile</button>
            <a href="database.php" class="cancel-btn">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
