<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* DB CONNECTION */
include('../includes/dbconnection.php');

/* Redirect if not logged in */
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$studentId = $_SESSION['student_id'];

/* FETCH STUDENT PRN */
$sql1 = "SELECT prn_number, fullname FROM tblstudents WHERE id = :sid LIMIT 1";
$q1 = $dbh->prepare($sql1);
$q1->bindParam(':sid', $studentId, PDO::PARAM_INT);
$q1->execute();
$student = $q1->fetch(PDO::FETCH_OBJ);

$prn = $student->prn_number ?? '';
$fullname = $student->fullname ?? 'Student';

/* FETCH LATEST PASS */
$sql2 = "SELECT PassNumber, FromDate, ToDate, cost, payment_result, pending_result
         FROM tblpass1
         WHERE prn_number = :prn
         ORDER BY PassNumber DESC
         LIMIT 1";
$q2 = $dbh->prepare($sql2);
$q2->bindParam(':prn', $prn, PDO::PARAM_STR);
$q2->execute();
$pass = $q2->fetch(PDO::FETCH_OBJ);

/* DEFAULT VALUES */
$passNumber = $pass->PassNumber ?? '-';
$fromDate   = $pass->FromDate ?? null;
$toDate     = $pass->ToDate ?? null;
$cost       = $pass->cost ?? '0';
$payment    = $pass->payment_result ?? 'Pending';
$pending    = $pass->pending_result ?? '0';

/* VALIDITY CALCULATION */
$today = date('Y-m-d');
$validityText = "Not Issued";
$validityClass = "danger";
$progress = 0;

if ($fromDate && $toDate) {
    if ($today <= $toDate) {
        $validityText = "Active";
        $validityClass = "success";
        $totalDays = max(1, (strtotime($toDate) - strtotime($fromDate)) / 86400);
        $usedDays  = max(0, (strtotime($today) - strtotime($fromDate)) / 86400);
        $progress  = min(100, ($usedDays / $totalDays) * 100);
    } else {
        $validityText = "Expired";
        $validityClass = "danger";
        $progress = 100;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pass Validity</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body {
    margin:0;
    font-family:'Poppins',sans-serif;
    background:#f0f2f5;
}
.container {
    max-width:450px;
    margin:40px auto;
    padding:0 15px;
}
.card {
    background:#fff;
    border-radius:18px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    transition:0.3s;
}
.card:hover {
    transform:translateY(-5px);
    box-shadow:0 15px 35px rgba(0,0,0,0.15);
}
.card h2 {
    text-align:center;
    color:#ff6a00;
    margin-bottom:20px;
}
.profile {
    text-align:center;
    margin-bottom:15px;
}
.profile img {
    width:80px;
    height:80px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:10px;
}
.info-row {
    display:flex;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px solid #eee;
    font-size:15px;
}
.info-row:last-child {
    border-bottom:none;
}
.badge {
    padding:5px 12px;
    border-radius:20px;
    font-size:13px;
}
.success {background:#28a745;color:#fff;}
.danger {background:#dc3545;color:#fff;}
.warning {background:#ffc107;color:#000;}
.progress {
    height:14px;
    border-radius:20px;
    overflow:hidden;
    background:#e9ecef;
    margin:20px 0;
}
.progress-bar {
    height:100%;
    background:linear-gradient(90deg,#ff6a00,#ff8c42);
    transition:width 1s ease-in-out;
}
.back-btn {
    display:block;
    text-align:center;
    text-decoration:none;
    background:#ff6a00;
    color:#fff;
    padding:12px;
    border-radius:10px;
    margin-top:15px;
}
.back-btn:hover {background:#e85d00;}
</style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="profile">
            <i class="fa fa-user-circle" style="font-size:60px;color:#ff6a00;"></i>
            <h3><?php echo htmlentities($fullname); ?></h3>
        </div>

        <div class="info-row">
            <span>Pass Number</span>
            <span><?php echo htmlentities($passNumber); ?></span>
        </div>

        <div class="info-row">
            <span>Status</span>
            <span class="badge <?php echo $validityClass; ?>"><?php echo $validityText; ?></span>
        </div>

        <div class="info-row">
            <span>From Date</span>
            <span><?php echo $fromDate ?: '-'; ?></span>
        </div>

        <div class="info-row">
            <span>To Date</span>
            <span><?php echo $toDate ?: '-'; ?></span>
        </div>

        <div class="info-row">
            <span>Pass Cost</span>
            <span>₹ <?php echo htmlentities($cost); ?></span>
        </div>

        <div class="info-row">
            <span>Payment Status</span>
            <span class="badge <?php echo ($payment=='Paid')?'success':'warning'; ?>"><?php echo htmlentities($payment); ?></span>
        </div>

        <div class="info-row">
            <span>Pending Result</span>
            <span><?php echo htmlentities($pending); ?></span>
        </div>

        <div class="progress">
            <div class="progress-bar" style="width:<?php echo round($progress); ?>%"></div>
        </div>

        <a href="dashboard.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

</body>
</html>
