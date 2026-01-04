<?php
session_start();
include('../includes/dbconnection.php');

$msg = "";

if (isset($_POST['login'])) {
    $prn = trim($_POST['prn']);
    $password = trim($_POST['password']);

    // Fetch student by PRN
    $sql = "SELECT * FROM tblstudents WHERE prn_number=:prn LIMIT 1";
    $query = $dbh->prepare($sql);
    $query->bindParam(':prn', $prn, PDO::PARAM_STR);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_OBJ);

    // Verify password
    if ($row && password_verify($password, $row->password)) {
        $_SESSION['student_id'] = $row->id;
        $_SESSION['student_name'] = $row->fullname;
        header("Location: dashboard.php");
        exit();
    } else {
        $msg = "Invalid PRN or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; }
        .login-box {
            max-width:420px; margin:80px auto;
            background:#fff; padding:30px;
            border-radius:15px;
            box-shadow:0 15px 35px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="login-box">
    <h3 class="text-center mb-3 text-primary">Student Login</h3>

    <?php if($msg){ ?>
        <div class="alert alert-danger text-center"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="post">
        <div class="mb-3">
            <label>PRN Number</label>
            <input type="text" name="prn" class="form-control" placeholder="Enter your PRN" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3">
        Don’t have an account? 
        <a href="student_register.php">Sign Up</a>
    </p>
</div>

</body>
</html>
