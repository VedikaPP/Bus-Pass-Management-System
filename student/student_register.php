<?php
session_start();
error_reporting(E_ALL);
include('../includes/dbconnection.php');

$msg = "";

if (isset($_POST['register'])) {

    $fullname  = trim($_POST['fullname']);
    $prn       = trim($_POST['prn']);
    $password  = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Password match check
    if ($password !== $cpassword) {
        $msg = "Passwords do not match";
    } else {

        // 1️⃣ Check PRN exists in pass table
        $sql = "SELECT prn_number FROM tblpass1 WHERE prn_number = :prn";
        $query = $dbh->prepare($sql);
        $query->bindParam(':prn', $prn, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() == 0) {
            $msg = "PRN not found. Contact transport office.";
        } else {

            // 2️⃣ Check PRN already registered
            $sql = "SELECT prn_number FROM tblstudents WHERE prn_number = :prn";
            $query = $dbh->prepare($sql);
            $query->bindParam(':prn', $prn, PDO::PARAM_STR);
            $query->execute();

            if ($query->rowCount() > 0) {
                $msg = "PRN already registered. Please login.";
            } else {

                // 3️⃣ Insert student
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO tblstudents (fullname, prn_number, password)
                        VALUES (:fullname, :prn, :password)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
                $query->bindParam(':prn', $prn, PDO::PARAM_STR);
                $query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

                if ($query->execute()) {
                    echo "<script>
                        alert('Signup Successful! Please Login');
                        window.location='student_login.php';
                    </script>";
                    exit;
                } else {
                    $msg = "Something went wrong. Try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; }
        .card {
            border-radius:15px;
            box-shadow:0 10px 25px rgba(0,0,0,.15);
        }
    </style>
</head>

<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="card p-4" style="width:420px;">
        <h3 class="text-center mb-3">Student Signup</h3>

        <?php if ($msg != "") { ?>
            <div class="alert alert-danger text-center"><?php echo $msg; ?></div>
        <?php } ?>

        <form method="post">
            <input class="form-control mb-3" type="text" name="fullname" placeholder="Full Name" required>
            <input class="form-control mb-3" type="text" name="prn" placeholder="PRN Number" required>
            <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
            <input class="form-control mb-3" type="password" name="cpassword" placeholder="Confirm Password" required>

            <button class="btn btn-primary w-100" name="register">Signup</button>
        </form>

        <p class="text-center mt-3">
            Already have an account?
            <a href="student_login.php">Login</a>
        </p>
    </div>
</div>
</body>
</html>
