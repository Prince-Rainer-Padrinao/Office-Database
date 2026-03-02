<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "peso_database");

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

    // Check which role they clicked and if the password matches
    if ($role === 'admin' && $pass === '12345') {
        $_SESSION['role'] = 'admin';
        header("Location: database.php"); // Refresh to clear the URL
        exit;
    } elseif ($role === 'user' && $pass === '1234') {
        $_SESSION['role'] = 'user';
        header("Location: database.php"); // Refresh to clear the URL
        exit;
    } else {
        $error = "❌ Incorrect password for " . strtoupper($role) . " mode.";
    }
}

// Handle Delete (Only if Admin)
if (isset($_GET['delete']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM employees WHERE id = $id");
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
        .container { background-color: white; border: 1px solid #000; width: 90%; margin: 0 auto; padding: 20px; box-shadow: 2px 2px 10px rgba(0,0,0,0.2); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #3b5336; color: white; }
        .btn { padding: 5px 15px; background: #dcdcdc; border: 1px solid #000; cursor: pointer; text-decoration: none; color: black; }
        .del-btn { background: #ff4d4d; color: white; font-weight: bold; }
        .big-btn { font-size: 20px; padding: 15px 40px; margin: 10px; display: inline-block; font-weight: bold; }
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
                <?php if ($_SESSION['role'] === 'admin') echo "<th>Action</th>"; ?>
            </tr>
            
            <?php
            $result = mysqli_query($conn, "SELECT * FROM employees");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['emp_name']}</td>";
                echo "<td>{$row['emp_code']}</td>";
                echo "<td>{$row['department']}</td>";
                echo "<td>₱{$row['net_pay']}</td>";
                
                // Show delete button only to admins
                if ($_SESSION['role'] === 'admin') {
                    echo "<td><a href='database.php?delete={$row['id']}' class='btn del-btn' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>