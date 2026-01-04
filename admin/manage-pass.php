<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    // Add new pass
    if (isset($_POST['addpass'])) {
        $passNumber = $_POST['passNumber'];
        $fullName = $_POST['fullName'];
        $contactNumber = $_POST['contactNumber'];
        $prnNumber = $_POST['prnNumber'];
        $fromDate = $_POST['fromDate'];

        $sql = "INSERT INTO tblpass1(PassNumber, FullName, ContactNumber, prn_number, FromDate) VALUES(:passNumber, :fullName, :contactNumber, :prnNumber, :fromDate)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':passNumber', $passNumber, PDO::PARAM_STR);
        $query->bindParam(':fullName', $fullName, PDO::PARAM_STR);
        $query->bindParam(':contactNumber', $contactNumber, PDO::PARAM_STR);
        $query->bindParam(':prnNumber', $prnNumber, PDO::PARAM_STR);
        $query->bindParam(':fromDate', $fromDate, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('New pass added successfully');</script>";
    }

    // Update pass
    if (isset($_POST['updatepass'])) {
        $passNumber = $_POST['editPassNumber'];
        $fullName = $_POST['editFullName'];
        $contactNumber = $_POST['editContactNumber'];
        $fromDate = $_POST['editFromDate'];
        $prnNumber = $_POST['editPrnNumber'];

        $sql = "UPDATE tblpass1 SET PassNumber=:passNumber, FullName=:fullName, ContactNumber=:contactNumber, FromDate=:fromDate WHERE prn_number=:prnNumber";
        $query = $dbh->prepare($sql);
        $query->bindParam(':passNumber', $passNumber, PDO::PARAM_STR);
        $query->bindParam(':fullName', $fullName, PDO::PARAM_STR);
        $query->bindParam(':contactNumber', $contactNumber, PDO::PARAM_STR);
        $query->bindParam(':fromDate', $fromDate, PDO::PARAM_STR);
        $query->bindParam(':prnNumber', $prnNumber, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Pass details updated successfully');</script>";
    }

    // Delete pass
    if (isset($_GET['delete'])) {
        $prnNumber = $_GET['delete'];
        $sql = "DELETE FROM tblpass1 WHERE prn_number=:prnNumber";
        $query = $dbh->prepare($sql);
        $query->bindParam(':prnNumber', $prnNumber, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Pass deleted successfully'); window.location.href='manage-pass.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bus Pass Management System | Manage Pass</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
<link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
<style>
body { font-family:'Poppins', sans-serif; background:#f4f7f9; display:flex; min-height:100vh; margin:0; }
.sidebar { width:240px; background-color:#1e90ff; color:#fff; flex-shrink:0; display:flex; flex-direction:column; }
.sidebar .logo { font-size:24px; font-weight:600; text-align:center; padding:20px; border-bottom:1px solid rgba(255,255,255,0.3); }
.sidebar nav a { display:flex; align-items:center; padding:15px 20px; color:#fff; text-decoration:none; transition:0.3s; }
.sidebar nav a i { margin-right:15px; font-size:18px; }
.sidebar nav a:hover { background-color: rgba(255,255,255,0.1); padding-left:25px; }
.sidebar nav a.active { background-color:#4682b4; }

.main-content { flex-grow:1; padding:20px 30px; }

.header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
.header h2 { color:#1e90ff; }
.header button.logout { padding:10px 20px; border:none; background-color:#1e90ff; color:#fff; border-radius:8px; cursor:pointer; transition:0.3s; }
.header button.logout:hover { background-color:#4682b4; }

.card { background:#fff; border-radius:12px; padding:20px; box-shadow:0 6px 20px rgba(0,0,0,0.1); transition:all 0.3s ease; }
.card:hover { transform:translateY(-5px); box-shadow:0 12px 30px rgba(0,0,0,0.15); }

table { width:100%; border-collapse:collapse; }
table thead { background:#1e90ff; color:#fff; }
table thead th { padding:12px; text-align:left; }
table tbody tr { border-bottom:1px solid #ddd; transition:0.3s; }
table tbody tr:hover { background:#f1f7ff; }
table tbody td { padding:12px; }

.btn-action { padding:6px 12px; border-radius:6px; color:#fff; text-decoration:none; margin-right:5px; transition:0.3s; display:inline-flex; align-items:center; justify-content:center; }
.btn-view { background:#28a745; }
.btn-view:hover { background:#218838; }
.btn-edit { background:#ffc107; color:#fff; }
.btn-edit:hover { background:#e0a800; }
.btn-delete { background:#dc3545; color:#fff; }
.btn-delete:hover { background:#c82333; }

.btn-add { background:#1e90ff; border:none; padding:10px 20px; border-radius:8px; color:#fff; cursor:pointer; margin-bottom:10px; }
.btn-add:hover { background:#4682b4; }

@media (max-width:768px){ .sidebar{width:60px;} .sidebar nav a span{display:none;} .main-content{padding:20px 15px;} }
</style>
</head>
<body>

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

<div class="main-content">
    <div class="header">
        <h2>Manage Pass</h2>
        <button class="logout" onclick="window.location.href='logout.php'"><i class="fa fa-sign-out-alt"></i> Logout</button>
    </div>

    <a href="add-pass.php" class="btn-add"><i class="fa fa-plus"></i> Add New Pass</a>
    <hr>

    <div class="card">
        <table id="passTable">
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Pass Number</th>
                    <th>Full Name</th>
                    <th>Contact Number</th>
                    <th>PRN</th>
                    <th>Creation Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tblpass1";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if($query->rowCount() > 0) {
                    foreach($results as $row) { ?>
                        <tr>
                            <td><?php echo htmlentities($cnt); ?></td>
                            <td><?php echo htmlentities($row->PassNumber); ?></td>
                            <td><?php echo htmlentities($row->FullName); ?></td>
                            <td><?php echo htmlentities($row->ContactNumber); ?></td>
                            <td><?php echo htmlentities($row->prn_number); ?></td>
                            <td><?php echo htmlentities($row->FromDate); ?></td>
                            <td>
                                <a class="btn-action btn-view" href="view-pass-detail.php?prn_number=<?php echo htmlentities($row->prn_number); ?>"><i class="fa fa-eye"></i></a>
                                <a class="btn-action btn-edit" href="edit-pass-detail.php?prn_number=<?php echo htmlentities($row->prn_number); ?>"><i class="fa fa-edit"></i></a>
                                <a class="btn-action btn-delete" href="manage-pass.php?delete=<?php echo htmlentities($row->prn_number); ?>" onclick="return confirm('Are you sure you want to delete this pass?');"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php $cnt++; } } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
<script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
<script>
$(document).ready(function() {
    $('#passTable').DataTable({
        "order": [[0, "asc"]],
        "lengthMenu": [5,10,25,50],
        "pageLength": 10
    });
});
</script>
</body>
</html>
<?php } ?>
