<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/dbconnection.php');

// Redirect if not logged in
if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Function to handle profile image upload
function uploadProfileImage($file) {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowed_extensions)) {
        throw new Exception('Invalid image format. Only jpg, jpeg, png, or gif allowed.');
    }

    $filename = md5($file['name'] . time()) . '.' . $extension;
    $upload_path = "images/" . $filename;

    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception('Failed to upload the image. Check file permissions.');
    }

    return $filename;
}

if (isset($_POST['submit'])) {
    try {
        $passnum = mt_rand(100000000, 999999999); // Random pass number
        $fullname = $_POST['fullname'] ?? '';
        $contact = $_POST['cnumber'] ?? '';
        $branch = $_POST['branch'] ?? '';
        $degree = $_POST['degree'] ?? '';
        $prn = $_POST['PRN'] ?? '';
        $category = $_POST['category'] ?? '';
        $source = $_POST['source'] ?? '';
        $destination = $_POST['destination'] ?? '';
        $fromdate = $_POST['fromdate'] ?? '';
        $todate = $_POST['todate'] ?? '';
        $bus_route = $_POST['bus_Route'] ?? 0;
        $total_cost = $_POST['totalCost'] ?? 0;
        $payment = $_POST['payment'] ?? 0;
        $pending_result = $total_cost - $payment;
        $payment_result = $payment;

        if (isset($_FILES['propic']) && $_FILES['propic']['error'] === 0) {
            $profile_image = uploadProfileImage($_FILES['propic']);
        } else {
            throw new Exception('Profile image upload failed or no file selected.');
        }

        $sql = "INSERT INTO tblpass1 (
                    PassNumber, FullName, ProfileImage, ContactNumber, prn_number, 
                    Category, Branch, Degree, Source, Destination, FromDate, 
                    ToDate, Cost, Bus_Route, Total_Cost, Payment_result, Pending_result
                ) VALUES (
                    :passnum, :fullname, :profile_image, :contact, :prn, 
                    :category, :branch, :degree, :source, :destination, :fromdate, 
                    :todate, :cost, :bus_route, :total_cost, :payment_result, :pending_result
                )";
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':passnum', $passnum);
        $query->bindParam(':fullname', $fullname);
        $query->bindParam(':profile_image', $profile_image);
        $query->bindParam(':contact', $contact);
        $query->bindParam(':prn', $prn);
        $query->bindParam(':category', $category);
        $query->bindParam(':branch', $branch);
        $query->bindParam(':degree', $degree);
        $query->bindParam(':source', $source);
        $query->bindParam(':destination', $destination);
        $query->bindParam(':fromdate', $fromdate);
        $query->bindParam(':todate', $todate);
        $query->bindParam(':cost', $bus_route);
        $query->bindParam(':bus_route', $bus_route);
        $query->bindParam(':total_cost', $total_cost);
        $query->bindParam(':payment_result', $payment_result);
        $query->bindParam(':pending_result', $pending_result);

        if ($query->execute()) {
            echo "<script>alert('Pass details added successfully.'); window.location='manage-pass.php';</script>";
            exit;
        } else {
            throw new Exception('Database error. Unable to add details.');
        }
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bus Pass Management | Add Pass</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
<link href="assets/css/style.css" rel="stylesheet" />
<style>
body { font-family:'Poppins', sans-serif; background:#f4f7f9; margin:0; display:flex; min-height:100vh; }
.sidebar { width:240px; background-color:#1e90ff; color:#fff; display:flex; flex-direction:column; }
.sidebar .logo { text-align:center; padding:20px; font-size:24px; font-weight:600; border-bottom:1px solid rgba(255,255,255,0.3); }
.sidebar nav a { display:flex; align-items:center; padding:15px 20px; color:#fff; text-decoration:none; transition:0.3s; }
.sidebar nav a i { margin-right:15px; }
.sidebar nav a:hover { background-color: rgba(255,255,255,0.1); padding-left:25px; }
.sidebar nav a.active { background-color:#4682b4; }

.main-content { flex-grow:1; padding:20px 30px; }
.header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
.header h2 { color:#1e90ff; margin:0; }
.header button.logout { padding:10px 20px; border:none; background:#1e90ff; color:#fff; border-radius:8px; cursor:pointer; }
.header button.logout:hover { background:#4682b4; }

.card { background:#fff; border-radius:12px; padding:20px; box-shadow:0 6px 20px rgba(0,0,0,0.1); transition:0.3s; }
.card:hover { transform:translateY(-5px); box-shadow:0 12px 30px rgba(0,0,0,0.15); }

.form-group { margin-bottom:15px; }
.form-group label { font-weight:600; margin-bottom:5px; display:block; }
.form-control { width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; }
.btn-primary { background:#1e90ff; border:none; color:#fff; padding:10px 20px; border-radius:8px; cursor:pointer; }
.btn-primary:hover { background:#4682b4; }

.payment-summary { background:#f1f7ff; padding:15px; border-radius:8px; margin-top:15px; }
.payment-summary p { margin:5px 0; font-weight:600; }
@media (max-width:768px){ .sidebar{width:60px;} .sidebar nav a span{display:none;} .main-content{padding:20px 15px;} }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">Bus Admin</div>
    <nav>
        <a href="dashboard.php"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a>
        <a href="manage-category.php"><i class="fa fa-bus"></i> <span>Manage Buses</span></a>
        <a href="driver management.php"><i class="fa fa-user"></i> <span>Manage Drivers</span></a>
        <a href="manage-pass.php" class="active"><i class="fa fa-file-alt"></i> <span>Manage Passes</span></a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Add New Pass</h2>
        <button class="logout" onclick="window.location.href='logout.php'"><i class="fa fa-sign-out-alt"></i> Logout</button>
    </div>

    <div class="card">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Profile Image</label>
                <input type="file" name="propic" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="cnumber" class="form-control" required maxlength="10" pattern="[0-9]+">
            </div>

            <div class="form-group">
                <label>Branch & Class</label>
                <input type="text" name="branch" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Degree/Diploma</label>
                <input type="text" name="degree" class="form-control" required>
            </div>

            <div class="form-group">
                <label>PRN No.</label>
                <input type="text" name="PRN" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control" required>
                    <?php
                    $sql2 = "SELECT * FROM tblcategory";
                    $query2 = $dbh->prepare($sql2);
                    $query2->execute();
                    $categories = $query2->fetchAll(PDO::FETCH_OBJ);
                    foreach ($categories as $category) {
                        echo "<option value='" . htmlentities($category->CategoryName) . "'>" . htmlentities($category->CategoryName) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Source</label>
                <input type="text" name="source" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Destination</label>
                <input type="text" name="destination" class="form-control" required>
            </div>

            <div class="form-group">
                <label>From Date</label>
                <input type="date" name="fromdate" class="form-control" required>
            </div>

            <div class="form-group">
                <label>To Date</label>
                <input type="date" name="todate" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Bus Route</label>
                <select id="busRoute" name="bus_Route" class="form-control">
                    <option value="">-- Select a route --</option>
                    <option value="22000">Route Wai</option>
                    <option value="14000">Route Satara</option>
                    <option value="22000">Route Umbraj</option>
                    <option value="18000">Route Rahimatpur</option>
                    <option value="14000">Route Kelghar</option>
                    <option value="20000">Route Mandve</option>
                    <option value="15000">Route Kumathefata</option>
                    <option value="17000">Route Molachaodha</option>
                </select>
            </div>

            <div class="form-group">
                <label>Total Cost</label>
                <input type="number" id="totalCost" name="totalCost" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Payment</label>
                <input type="number" id="payment" name="payment" class="form-control">
            </div>

            <div class="payment-summary">
                <p id="paymentResult">Amount Paid: ₹ 0</p>
                <p id="pendingResult">Pending Amount: ₹ 0</p>
            </div>

            <div class="form-group" style="margin-top:15px;">
                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Pass</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='manage-pass.php';"> 
        <i class="fa fa-arrow-left"></i> Go Back
    </button>
</div>

            </div>
        </form>
    </div>
</div>

<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script>
const busRouteSelect = document.getElementById('busRoute');
const totalCostInput = document.getElementById('totalCost');
const paymentInput = document.getElementById('payment');
const paymentResult = document.getElementById('paymentResult');
const pendingResult = document.getElementById('pendingResult');

busRouteSelect.addEventListener('change', function() {
    const cost = parseFloat(busRouteSelect.value) || 0;
    totalCostInput.value = cost.toFixed(2);
    paymentResult.textContent = `Amount Paid: ₹ 0`;
    pendingResult.textContent = `Pending Amount: ₹ ${cost.toFixed(2)}`;
});

paymentInput.addEventListener('input', function() {
    const totalCost = parseFloat(totalCostInput.value) || 0;
    const payment = parseFloat(paymentInput.value) || 0;
    const pending = totalCost - payment >= 0 ? totalCost - payment : 0;
    paymentResult.textContent = `Amount Paid: ₹ ${payment.toFixed(2)}`;
    pendingResult.textContent = `Pending Amount: ₹ ${pending.toFixed(2)}`;
});
</script>
</body>
</html>
