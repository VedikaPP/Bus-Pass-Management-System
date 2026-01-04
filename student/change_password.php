<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* Redirect if not logged in */
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

/* DB CONNECTION */
include('../includes/dbconnection.php');

$studentId = $_SESSION['student_id'];
$success = false;
$errorMessage = '';

if (isset($_POST['update'])) {
    $newPass = trim($_POST['new_password']);
    $confirmPass = trim($_POST['confirm_password']);

    if (empty($newPass) || empty($confirmPass)) {
        $errorMessage = "Please fill in both fields.";
    } elseif ($newPass !== $confirmPass) {
        $errorMessage = "Passwords do not match.";
    } else {
        $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
        $sql = "UPDATE tblstudents SET password=:password WHERE id=:sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':password', $hashedPass, PDO::PARAM_STR);
        $query->bindParam(':sid', $studentId, PDO::PARAM_INT);

        if ($query->execute()) {
            $success = true;
        } else {
            $errorMessage = "Something went wrong. Please try again.";
        }
    }
}

/* Fetch student name for display */
$sql = "SELECT fullname FROM tblstudents WHERE id=:sid LIMIT 1";
$q = $dbh->prepare($sql);
$q->bindParam(':sid', $studentId, PDO::PARAM_INT);
$q->execute();
$student = $q->fetch(PDO::FETCH_OBJ);
$fullname = $student->fullname ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password | Student Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f4f7f9;
    margin: 0;
    display: flex;
    min-height: 100vh;
}
.container {
    max-width: 450px;
    margin: 50px auto;
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    color: #ff6a00;
    margin-bottom: 20px;
}
.form-group {
    margin-bottom: 15px;
    position: relative;
}
input.password-input {
    border-radius: 8px;
    padding: 10px 40px 10px 10px;
    width: 100%;
    border: 1px solid #ccc;
    font-family: inherit;
}
.eye-icon {
    position: absolute;
    right: 10px;
    top: 37px;
    cursor: pointer;
    color: #888;
}
button {
    background: #ff6a00;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    width: 100%;
    font-weight: 600;
}
button:hover {
    background: #e85d00;
}
.back-btn {
    display: block;
    text-align: center;
    margin-top: 15px;
    text-decoration: none;
    color: #ff6a00;
}
.back-btn:hover {
    color: #e85d00;
}

/* ===== Toast Popup ===== */
.toast-message {
    visibility: hidden;
    min-width: 250px;
    background-color: #28a745;
    color: #fff;
    text-align: center;
    border-radius: 8px;
    padding: 15px;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    font-weight: 500;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}
.toast-message.show {
    visibility: visible;
    opacity: 1;
}
.toast-error {
    background-color: #dc3545;
}
</style>
</head>
<body>

<div class="container">
    <h2>Change Password</h2>

    <form method="post">
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Enter new password" required id="new_password" class="password-input">
            <i class="fa fa-eye eye-icon" toggle="#new_password"></i>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required id="confirm_password" class="password-input">
            <i class="fa fa-eye eye-icon" toggle="#confirm_password"></i>
        </div>

        <button type="submit" name="update">Update Password</button>
    </form>

    <a href="dashboard.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
</div>

<!-- Toast Popup -->
<div id="toast" class="toast-message <?php echo $errorMessage ? 'toast-error':''; ?>">
    <?php
        if ($success) echo "Password updated successfully!";
        elseif ($errorMessage) echo htmlentities($errorMessage);
    ?>
</div>

<script>
// Show toast if PHP sets message
window.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');
    if (toast.textContent.trim() !== "") {
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    }

    // Show/hide password toggle
    const toggleIcons = document.querySelectorAll('.eye-icon');
    toggleIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const input = document.querySelector(icon.getAttribute('toggle'));
            if (input.type === "password") {
                input.type = "text"; // show password as text
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = "password"; // hide password
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });
});
</script>

</body>
</html>
