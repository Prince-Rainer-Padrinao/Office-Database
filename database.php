<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "peso_database");

// --- EXPORT TO EXCEL (CSV) ---
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="PESO_Employee_Data.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Name', 'Code', 'Email', 'DOB', 'Department', 'Location', 'Basic Salary', 'HRA', 'Medical', 'Conveyance', 'Deductions', 'Net Pay', 'Status'));
    
    $result = mysqli_query($conn, "SELECT * FROM employees");
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
        $_SESSION['role'] = 'admin';
        header("Location: database.php"); 
        exit;
    } elseif ($role === 'user' && $pass === '1234') {
        $_SESSION['role'] = 'user';
        header("Location: database.php"); 
        exit;
    } else {
        $error = "❌ Incorrect password for " . strtoupper($role) . " mode.";
    }
}

// Handle Delete Single Row (Only if Admin)
if (isset($_GET['delete']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM employees WHERE id = $id");
    header("Location: database.php");
    exit;
}

// --- TOGGLE STATUS (Only if Admin) ---
if (isset($_GET['toggle']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $id = (int)$_GET['toggle'];
    $check_query = mysqli_query($conn, "SELECT status FROM employees WHERE id = $id");
    if ($row = mysqli_fetch_assoc($check_query)) {
        $new_status = ($row['status'] === 'Active') ? 'Inactive' : 'Active';
        mysqli_query($conn, "UPDATE employees SET status = '$new_status' WHERE id = $id");
    }
    header("Location: database.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Office Database</title>
    <style>
        body { font-family: Calibri, sans-serif; background-color: #f0f0f0; padding: 20px; }
        .container { background-color: white; border: 1px solid #000; width: 95%; margin: 0 auto; padding: 20px; box-shadow: 2px 2px 10px rgba(0,0,0,0.2); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: middle; }
        th { background-color: #3b5336; color: white; }
        .btn { padding: 5px 15px; background: #dcdcdc; border: 1px solid #000; cursor: pointer; text-decoration: none; color: black; display: inline-block; margin-left: 5px; }
        .del-btn { background: #ff4d4d; color: white; font-weight: bold; }
        .excel-btn { background: #217346; color: white; font-weight: bold; border-color: #1e5c3a; }
        .big-btn { font-size: 20px; padding: 15px 40px; margin: 10px; display: inline-block; font-weight: bold; }
        .status-active { color: #217346; font-weight: bold; }
        .status-inactive { color: #ff4d4d; font-weight: bold; }
        .toggle-btn { font-size: 12px; padding: 2px 6px; background-color: #f0f0f0; border: 1px solid #999; border-radius: 3px; color: black; text-decoration: none; margin-left: 8px;}
        .toggle-btn:hover { background-color: #ddd; }
        .expand-btn { background-color: #0563C1; color: white; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <?php if (!isset($_SESSION['role'])): ?>
        
        <?php if (!isset($_GET['login'])): ?>
            <div style="text-align: center; padding: 50px 0;">
                <h2>Who is logging in?</h2>
                <br>
                <a href="database.php?login=admin" class="btn big-btn" style="background-color: #3b5336; color: white;">👑 Admin</a>
                <a href="database.php?login=user" class="btn big-btn" style="background-color: #555; color: white;">👤 User</a>
                <br><br><br>
                <a href="index.php" style="color: #0563C1; text-decoration: underline;">⬅ Back to Data Entry</a>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px 0;">
                <h2>Enter <?php echo ucfirst(htmlspecialchars($_GET['login'])); ?> Password</h2>
                <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
                <form method="POST" action="database.php?login=<?php echo htmlspecialchars($_GET['login']); ?>">
                    <input type="hidden" name="attempted_role" value="<?php echo htmlspecialchars($_GET['login']); ?>">
                    <input type="password" name="password" placeholder="Enter Password" required style="padding: 10px; font-size: 16px; width: 200px;">
                    <button type="submit" class="btn" style="padding: 10px 20px; font-size: 16px;">Login</button>
                </form>
                <br><br>
                <a href="database.php" style="color: #555; text-decoration: none;">⬅ Choose a different role</a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>📊 Employee Records (Mode: <?php echo strtoupper($_SESSION['role']); ?>)</h2>
            <div>
                <a href="database.php?export=excel" class="btn excel-btn">📥 Export to Excel</a>
                <a href="index.php" class="btn">➕ Add New</a>
                <a href="database.php?logout=true" class="btn" style="background-color: #444; color: white;">🚪 Logout</a>
            </div>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Dept</th>
                <th>Net Pay</th>
                <th>Status</th>
                <th>Action</th> </tr>
            
            <?php
            $result = mysqli_query($conn, "SELECT * FROM employees");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['emp_name']}</td>";
                echo "<td>{$row['emp_code']}</td>";
                echo "<td>{$row['department']}</td>";
                echo "<td>₱{$row['net_pay']}</td>";
                
                // Status Column
                $current_status = isset($row['status']) ? $row['status'] : 'Active';
                $status_class = ($current_status === 'Active') ? 'status-active' : 'status-inactive';
                
                echo "<td>";
                echo "<span class='{$status_class}'>{$current_status}</span>";
                
                if ($_SESSION['role'] === 'admin') {
                    // Added the exact confirmation prompt here!
                    echo "<a href='database.php?toggle={$row['id']}' class='toggle-btn' onclick='return confirm(\"Are you sure you want to change this employee status to something else?\")'>🔄 Toggle</a>";
                }
                echo "</td>";
                
                // Action Column (Expand & Delete)
                echo "<td>";
                echo "<button type='button' class='btn expand-btn' onclick='toggleDetails({$row['id']})'>🔍 Expand</button>";
                
                if ($_SESSION['role'] === 'admin') {
                    echo "<a href='database.php?delete={$row['id']}' class='btn del-btn' onclick='return confirm(\"Are you sure you want to completely delete this record?\")'>🗑️ Delete</a>";
                }
                echo "</td>";
                echo "</tr>";

                // --- THE HIDDEN EXPANDED ROW ---
                echo "<tr id='details-{$row['id']}' style='display: none; background-color: #e2e8f0;'>";
                echo "<td colspan='7' style='padding: 15px; border: 2px solid #0563C1;'>";
                echo "<strong>📧 Email:</strong> {$row['email']} &nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<strong>🎂 DOB:</strong> {$row['dob']} &nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<strong>📍 Location:</strong> {$row['location']} <br><br>";
                echo "<strong>💵 Basic Salary:</strong> ₱{$row['basic_salary']} &nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<strong>🏠 HRA:</strong> ₱{$row['hra']} &nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<strong>🏥 Medical:</strong> ₱{$row['medical']} &nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<strong>🚗 Conveyance:</strong> ₱{$row['conveyance']} &nbsp;&nbsp;|&nbsp;&nbsp; ";
                echo "<strong>📉 Deductions:</strong> ₱{$row['deductions']}";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php endif; ?>
</div>

<script>
    function toggleDetails(id) {
        var hiddenRow = document.getElementById('details-' + id);
        if (hiddenRow.style.display === 'none') {
            hiddenRow.style.display = 'table-row';
        } else {
            hiddenRow.style.display = 'none';
        }
    }
</script>

</body>
</html>
