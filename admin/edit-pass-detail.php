<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(strlen($_SESSION['bpmsaid']==0)) {
    header('location:logout.php');
    exit;
}

// Get PRN number from URL
$prnNumber = isset($_GET['prn_number']) ? trim($_GET['prn_number']) : '';

if(!$prnNumber){
    echo "<script>alert('Invalid pass.');window.location.href='manage-pass.php';</script>";
    exit;
}

// Fetch pass details
$sql = "SELECT * FROM tblpass1 WHERE prn_number=:prnNumber";
$query = $dbh->prepare($sql);
$query->bindParam(':prnNumber', $prnNumber, PDO::PARAM_STR);
$query->execute();
$passData = $query->fetch(PDO::FETCH_OBJ);

if(!$passData){
    echo "<script>alert('Pass not found.');window.location.href='manage-pass.php';</script>";
    exit;
}

// Update pass details
if(isset($_POST['updatepass'])){
    $passNumber = $_POST['passNumber'];
    $fullName = $_POST['fullName'];
    $contactNumber = $_POST['contactNumber'];
    $category = $_POST['category'];
    $branch = $_POST['branch'];
    $degree = $_POST['degree'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $cost = $_POST['cost'];
    $paymentMade = $_POST['paymentMade'];
    $pendingAmount = $_POST['pendingAmount'];

    $sqlUpdate = "UPDATE tblpass1 SET 
                    PassNumber=:passNumber,
                    FullName=:fullName,
                    ContactNumber=:contactNumber,
                    Category=:category,
                    Branch=:branch,
                    Degree=:degree,
                    Source=:source,
                    Destination=:destination,
                    FromDate=:fromDate,
                    ToDate=:toDate,
                    Cost=:cost,
                    Payment_result=:paymentMade,
                    Pending_result=:pendingAmount
                  WHERE prn_number=:prnNumber";

    $updateQuery = $dbh->prepare($sqlUpdate);
    $updateQuery->bindParam(':passNumber', $passNumber, PDO::PARAM_STR);
    $updateQuery->bindParam(':fullName', $fullName, PDO::PARAM_STR);
    $updateQuery->bindParam(':contactNumber', $contactNumber, PDO::PARAM_STR);
    $updateQuery->bindParam(':category', $category, PDO::PARAM_STR);
    $updateQuery->bindParam(':branch', $branch, PDO::PARAM_STR);
    $updateQuery->bindParam(':degree', $degree, PDO::PARAM_STR);
    $updateQuery->bindParam(':source', $source, PDO::PARAM_STR);
    $updateQuery->bindParam(':destination', $destination, PDO::PARAM_STR);
    $updateQuery->bindParam(':fromDate', $fromDate, PDO::PARAM_STR);
    $updateQuery->bindParam(':toDate', $toDate, PDO::PARAM_STR);
    $updateQuery->bindParam(':cost', $cost, PDO::PARAM_STR);
    $updateQuery->bindParam(':paymentMade', $paymentMade, PDO::PARAM_STR);
    $updateQuery->bindParam(':pendingAmount', $pendingAmount, PDO::PARAM_STR);
    $updateQuery->bindParam(':prnNumber', $prnNumber, PDO::PARAM_STR);
    $updateQuery->execute();

    echo "<script>alert('Pass updated successfully');window.location.href='manage-pass.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Pass Details</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
<style>
body { font-family:'Poppins', sans-serif; background:#f4f7f9; margin:0; padding:40px; }
.container { max-width:700px; background:#fff; padding:30px; margin:auto; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.1); }
h2 { color:#1e90ff; margin-bottom:25px; text-align:center; }
input.form-control, select.form-control { width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #ddd; }
label { font-weight:600; margin-bottom:5px; display:block; }
.btn-update { background:#1e90ff; border:none; padding:12px 20px; border-radius:8px; color:#fff; cursor:pointer; width:100%; font-size:16px; }
.btn-update:hover { background:#4682b4; }
.btn-back { display:inline-block; margin-bottom:20px; background:#6c757d; color:#fff; padding:8px 15px; border-radius:6px; text-decoration:none; transition:0.3s; }
.btn-back:hover { background:#5a6268; }
</style>
</head>
<body>

<div class="container">
    <a href="manage-pass.php" class="btn-back"><i class="fa fa-arrow-left"></i> Back</a>
    <h2>Edit Pass Details</h2>

    <form method="post">
        <label>Pass Number</label>
        <input type="text" name="passNumber" class="form-control" value="<?php echo htmlentities($passData->PassNumber); ?>" required>

        <label>Full Name</label>
        <input type="text" name="fullName" class="form-control" value="<?php echo htmlentities($passData->FullName); ?>" required>

        <label>Contact Number</label>
        <input type="text" name="contactNumber" class="form-control" value="<?php echo htmlentities($passData->ContactNumber); ?>" required>

        <label>PRN Number (readonly)</label>
        <input type="text" class="form-control" value="<?php echo htmlentities($passData->prn_number); ?>" readonly>

        <label>Category</label>
        <input type="text" name="category" class="form-control" value="<?php echo htmlentities($passData->Category); ?>">

        <label>Branch</label>
        <input type="text" name="branch" class="form-control" value="<?php echo htmlentities($passData->Branch); ?>">

        <label>Degree</label>
        <input type="text" name="degree" class="form-control" value="<?php echo htmlentities($passData->Degree); ?>">

        <label>Source</label>
        <input type="text" name="source" class="form-control" value="<?php echo htmlentities($passData->Source); ?>">

        <label>Destination</label>
        <input type="text" name="destination" class="form-control" value="<?php echo htmlentities($passData->Destination); ?>">

        <label>From Date</label>
        <input type="date" name="fromDate" class="form-control" value="<?php echo htmlentities($passData->FromDate); ?>">

        <label>To Date</label>
        <input type="date" name="toDate" class="form-control" value="<?php echo htmlentities($passData->ToDate); ?>">

        <label>Cost</label>
        <input type="number" name="cost" class="form-control" value="<?php echo htmlentities($passData->Cost); ?>">

        <label>Payment Made</label>
        <input type="number" name="paymentMade" class="form-control" value="<?php echo htmlentities($passData->Payment_result); ?>">

        <label>Pending Amount</label>
        <input type="number" name="pendingAmount" class="form-control" value="<?php echo htmlentities($passData->Pending_result); ?>">

        <button type="submit" name="updatepass" class="btn-update"><i class="fa fa-save"></i> Update Pass</button>
    </form>
</div>

</body>
</html>
