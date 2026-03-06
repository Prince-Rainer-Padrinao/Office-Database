<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "peso_database");

// --- SMART IMPORT FROM GOOGLE FORMS (CSV) ---
if (isset($_POST["import_csv"]) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    if($_FILES['csv_file']['name']) {
        $filename = explode(".", $_FILES['csv_file']['name']);
        if(strtolower(end($filename)) == "csv") {
            $handle = fopen($_FILES['csv_file']['tmp_name'], "r");
            
            // Skip the header row from Google Sheets
            fgetcsv($handle); 
            
            $added = 0;
            $skipped = 0;

            while($data = fgetcsv($handle)) {
                // Skip empty rows at the bottom of the CSV
                if(count($data) < 47) continue;

                // Function to clean special characters (like apostrophes in names)
                $clean = function($str) use ($conn) { return mysqli_real_escape_string($conn, $str ?? ''); };

                // Map Google Forms Columns to Variables (Index 0 is Timestamp)
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
                
                $passport_number = $clean($data[15]);
                
                // --- DUPLICATE CHECK ---
                $check = mysqli_query($conn, "SELECT id FROM ofw_profiles WHERE passport_number = '$passport_number'");
                if (mysqli_num_rows($check) > 0) {
                    $skipped++; // Already exists, skip to the next row!
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

                // Insert the new person!
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
            echo "<script>alert('✅ Import Complete!\\n\\nAdded: $added new profiles\\nSkipped: $skipped duplicates'); window.location.href='database.php';</script>";
        }
    }
}

// --- EXPORT TO EXCEL (CSV) ---
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="OFW_Profiles_Export.csv"');
    $output = fopen('php://output', 'w');
    
    $query = "SELECT * FROM ofw_profiles ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    
    $fields = mysqli_fetch_fields($result);
    $headers = [];
    foreach ($fields as $field) {
        $headers[] = strtoupper(str_replace('_', ' ', $field->name));
    }
    fputcsv($output, $headers);
    
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: database.php");
    exit;
}

// Handle Login
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password']) && isset($_POST['attempted_role'])) {
    $role = $_POST['attempted_role'];
    $pass = $_POST['password'];

    if ($role === 'admin' && $pass === '12345') {
        $_SESSION['role'] = 'admin'; header("Location: database.php"); exit;
    } elseif ($role === 'user' && $pass === '1234') {
        $_SESSION['role'] = 'user'; header("Location: database.php"); exit;
    } else {
        $error = "❌ Incorrect password.";
    }
}

// Handle Make Active (Only Admin)
if (isset($_GET['make_active']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $id = (int)$_GET['make_active'];
    mysqli_query($conn, "UPDATE ofw_profiles SET current_status = 'Active OFW' WHERE id = $id");
    header("Location: database.php");
    exit;
}

// Handle Delete (Only Admin)
if (isset($_GET['delete']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM ofw_profiles WHERE id = $id");
    header("Location: database.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFW Database</title>
    <style>
        body { font-family: Calibri, sans-serif; background-color: #f0f0f0; padding: 20px; }
        .container { background-color: white; border: 1px solid #000; width: 95%; margin: 0 auto; padding: 20px; box-shadow: 2px 2px 10px rgba(0,0,0,0.2); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: middle; }
        th { background-color: #3b5336; color: white; }
        .btn { padding: 5px 10px; background: #dcdcdc; border: 1px solid #000; cursor: pointer; text-decoration: none; color: black; display: inline-block; font-size: 13px;}
        .del-btn { background: #ff4d4d; color: white; font-weight: bold; }
        .edit-btn { background: #ffca28; color: black; font-weight: bold; } 
        .expand-btn { background-color: #0563C1; color: white; font-weight: bold; }
        .excel-btn { background: #217346; color: white; font-weight: bold; }
        .import-btn { background: #0563C1; color: white; font-weight: bold; }
        .status-active { color: #217346; font-weight: bold; }
        .status-inactive { color: #c00; font-weight: bold; }
        
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; background-color: #eef2ed; padding: 20px; border: 2px solid #3b5336; margin: 5px 0; }
        .details-section { background: white; padding: 15px; border: 1px solid #ccc; }
        .details-section h4 { margin-top: 0; color: #3b5336; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .details-section p { margin: 5px 0; font-size: 13px;}
    </style>
</head>
<body>

<div class="container">
    <?php if (!isset($_SESSION['role'])): ?>
        <?php if (!isset($_GET['login'])): ?>
            <div style="text-align: center; padding: 50px 0;">
                <h2>Who is logging in?</h2><br>
                <a href="database.php?login=admin" class="btn" style="background-color: #3b5336; color: white; padding: 15px 30px; font-size: 20px;">👑 Admin</a>
                <a href="database.php?login=user" class="btn" style="background-color: #555; color: white; padding: 15px 30px; font-size: 20px;">👤 User</a><br><br><br>
                <a href="index.php">⬅ Back to Data Entry</a>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px 0;">
                <h2>Enter <?php echo ucfirst(htmlspecialchars($_GET['login'])); ?> Password</h2>
                <p style="color: red;"><?php echo $error; ?></p>
                <form method="POST" action="database.php?login=<?php echo htmlspecialchars($_GET['login']); ?>">
                    <input type="hidden" name="attempted_role" value="<?php echo htmlspecialchars($_GET['login']); ?>">
                    <input type="password" name="password" required style="padding: 10px;">
                    <button type="submit" class="btn" style="padding: 10px;">Login</button>
                </form>
            </div>
        <?php endif; ?>
    <?php else: ?>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h2>📊 OFW Profiles (Mode: <?php echo strtoupper($_SESSION['role']); ?>)</h2>
            <div style="display: flex; align-items: center; gap: 10px;">
                
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <form method="POST" enctype="multipart/form-data" style="margin: 0; padding: 5px; border: 1px dashed #3b5336; background-color: #e8f4ea;">
                        <input type="file" name="csv_file" accept=".csv" required style="font-size: 12px;">
                        <button type="submit" name="import_csv" class="btn import-btn">📂 Import CSV</button>
                    </form>
                <?php endif; ?>

                <a href="database.php?export=excel" class="btn excel-btn">📥 Export</a>
                <a href="index.php" class="btn">➕ Add New</a>
                <a href="database.php?logout=true" class="btn" style="background-color: #444; color: white;">🚪 Logout</a>
            </div>
        </div>

        <table>
            <tr>
                <th>ID</th><th>Name</th><th>Passport No.</th><th>Country</th><th>Current Status</th><th>Action</th>
            </tr>
            
            <?php
            $result = mysqli_query($conn, "SELECT * FROM ofw_profiles ORDER BY id DESC");
            while ($row = mysqli_fetch_assoc($result)) {
                $full_name = strtoupper($row['last_name']) . ", " . $row['first_name'];
                $status = $row['current_status'];
                $is_active = ($status === 'Active OFW');
                $status_class = $is_active ? 'status-active' : 'status-inactive';

                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td><strong>{$full_name}</strong></td>";
                echo "<td>{$row['passport_number']}</td>";
                echo "<td>{$row['country_employment']}</td>";
                
                echo "<td><span class='{$status_class}'>{$status}</span><br>";
                if ($_SESSION['role'] === 'admin') {
                    if ($is_active) {
                        echo "<a href='edit_status.php?id={$row['id']}' class='btn' style='font-size: 11px; margin-top: 5px; color: #c00;'>🔴 Make Inactive</a>";
                    } else {
                        echo "<a href='database.php?make_active={$row['id']}' class='btn' style='font-size: 11px; margin-top: 5px; color: #217346;' onclick='return confirm(\"Set this OFW back to Active status?\")'>🟢 Make Active</a>";
                    }
                }
                echo "</td>";
                
                echo "<td>";
                echo "<button type='button' class='btn expand-btn' onclick='toggleDetails({$row['id']})'>🔍 Expand</button> ";
                
                if ($_SESSION['role'] === 'admin') {
                    echo "<a href='edit.php?id={$row['id']}' class='btn edit-btn'>✏️ Edit</a> ";
                    echo "<a href='database.php?delete={$row['id']}' class='btn del-btn' onclick='return confirm(\"Delete permanently?\")'>🗑️ Delete</a>";
                }
                echo "</td></tr>";

                // --- EXPANDED ROW LOGIC ---
                echo "<tr id='details-{$row['id']}' style='display: none;'>";
                echo "<td colspan='6'>";
                echo "<div class='details-grid'>";
                
                echo "<div class='details-section'><h4>I. Personal Info</h4>
                      <p><strong>DOB/Age:</strong> {$row['dob']} ({$row['age']} yrs)</p>
                      <p><strong>Sex:</strong> {$row['sex']} | <strong>Civil:</strong> {$row['civil_status']}</p>
                      <p><strong>Contact:</strong> {$row['contact_number']} | <strong>Email:</strong> {$row['email']}</p>
                      <p><strong>Address:</strong> {$row['ph_address']}, {$row['barangay']}, {$row['municipality']}, {$row['province']}</p></div>";

                echo "<div class='details-section'><h4>II. IDs & Passport</h4>
                      <p><strong>Passport Exp:</strong> {$row['passport_expiry']}</p>
                      <p><strong>OEC:</strong> {$row['oec_number']} | <strong>DMW:</strong> {$row['dmw_number']}</p>
                      <p><strong>SSS:</strong> {$row['sss_number']} | <strong>PhilHealth:</strong> {$row['philhealth_number']}</p></div>";

                echo "<div class='details-section'><h4>III. Employment Abroad</h4>
                      <p><strong>Job:</strong> {$row['job_position']} ({$row['employment_type']})</p>
                      <p><strong>Employer:</strong> {$row['employer_name']}</p>
                      <p><strong>Deployed:</strong> {$row['deployment_date']} | <strong>Duration:</strong> {$row['contract_duration']}</p>
                      <p><strong>Salary:</strong> {$row['monthly_salary']}</p></div>";

                echo "<div class='details-section'><h4>IV. Skills & Training</h4>
                      <p><strong>Education:</strong> {$row['education']} ({$row['field_of_study']})</p>
                      <p><strong>TESDA:</strong> {$row['tesda_cert']}</p>
                      <p><strong>Other Skills:</strong> {$row['other_skills']}</p></div>";

                echo "<div class='details-section'><h4>V. Family Info</h4>
                      <p><strong>Next of Kin:</strong> {$row['kin_name']} ({$row['kin_relationship']})</p>
                      <p><strong>Contact:</strong> {$row['kin_contact']}</p>
                      <p><strong>Dependents:</strong> {$row['dependents']}</p></div>";

                echo "<div class='details-section'><h4>VI. Assistance & Concerns</h4>
                      <p><strong>Availed Assistance?</strong> {$row['availed_assistance']} ({$row['assistance_specify']})</p>
                      <p><strong>Concerns:</strong> {$row['concerns']}</p>
                      <p><strong>Others:</strong> {$row['concerns_others']}</p></div>";

                echo "<div class='details-section'><h4>VII. Emergency</h4>
                      <p><strong>Contact:</strong> {$row['emergency_contact_name']} ({$row['emergency_relationship']})</p>
                      <p><strong>Number:</strong> {$row['emergency_contact_number']}</p></div>";

                echo "<div class='details-section'><h4>VIII. Consent</h4>
                      <p><strong>Agreed:</strong> {$row['privacy_consent']}</p>
                      <p><strong>Signed By:</strong> {$row['signature']} on {$row['date_signed']}</p></div>";

                echo "</div></td></tr>";
            }
            ?>
        </table>
    <?php endif; ?>
</div>

<script>
    function toggleDetails(id) {
        var row = document.getElementById('details-' + id);
        row.style.display = (row.style.display === 'none') ? 'table-row' : 'none';
    }
</script>
</body>
</html>
