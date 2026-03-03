<?php
session_start();
// Kick them out if they aren't an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: database.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "peso_database");

// Check if an ID was passed in the URL
if (!isset($_GET['id'])) {
    header("Location: database.php");
    exit;
}
$id = (int)$_GET['id'];

// If the form was submitted, update the database and send them back
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_status'])) {
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
    mysqli_query($conn, "UPDATE ofw_profiles SET current_status = '$new_status' WHERE id = $id");
    header("Location: database.php");
    exit;
}

// Fetch the person's name so the Admin knows who they are editing
$result = mysqli_query($conn, "SELECT first_name, last_name FROM ofw_profiles WHERE id = $id");
$person = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change OFW Status</title>
    <style>
        body { font-family: Calibri, sans-serif; background-color: #f0f0f0; padding: 40px; display: flex; justify-content: center; }
        .box { background: white; padding: 30px; border: 1px solid #000; box-shadow: 2px 2px 10px rgba(0,0,0,0.2); width: 400px; }
        .radio-group { display: flex; flex-direction: column; gap: 15px; margin: 20px 0; }
        .btn { padding: 10px; background: #3b5336; color: white; border: 1px solid #000; cursor: pointer; font-weight: bold; width: 100%; }
    </style>
</head>
<body>

<div class="box">
    <h2 style="color: #c00; margin-top: 0;">Update Inactive Status</h2>
    <p>Please specify the new status for:<br><strong><?php echo $person['first_name'] . ' ' . $person['last_name']; ?></strong></p>

    <form method="POST">
        <div class="radio-group">
            <label><input type="radio" name="new_status" value="Vacationing in PH" required> Vacationing in PH</label>
            <label><input type="radio" name="new_status" value="End of Contract"> End of Contract</label>
            <label><input type="radio" name="new_status" value="Repatriated"> Repatriated</label>
            <label><input type="radio" name="new_status" value="Returning to Host Country"> Returning to Host Country</label>
            <label><input type="radio" name="new_status" value="Jobseeker"> Jobseeker</label>
        </div>
        <button type="submit" class="btn">Save Change</button>
    </form>
    
    <br>
    <div style="text-align: center;">
        <a href="database.php" style="color: #555; text-decoration: none;">Cancel and go back</a>
    </div>
</div>

</body>
</html>