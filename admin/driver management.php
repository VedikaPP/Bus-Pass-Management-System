<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
    header('location:logout.php');
} else {

// Delete driver
if(isset($_GET['delid'])) {
    $rid=intval($_GET['delid']);
    $sql="DELETE FROM tbldriver WHERE ID=:rid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Driver deleted successfully');</script>"; 
    echo "<script>window.location.href='driver management.php';</script>";
}

// Add new driver
if(isset($_POST['add_driver'])){
    $name = $_POST['drivername'];
    $phone = $_POST['phonenumber'];
    $sql = "INSERT INTO tbldriver (DriverName, PhoneNumber, CreatedDate) VALUES (:name, :phone, NOW())";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':phone', $phone, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Driver added successfully');</script>";
    echo "<script>window.location.href='driver management.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bus Pass Management System | Manage Drivers</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />

<style>
body {
    font-family: 'Poppins', sans-serif;
    margin:0; padding:0;
    background:#f4f7f9;
    display:flex;
    min-height:100vh;
}

/* Sidebar */
.sidebar {
    width:240px;
    background-color:#1e90ff;
    color:#fff;
    flex-shrink:0;
    display:flex;
    flex-direction:column;
}
.sidebar .logo {
    font-size:24px;
    font-weight:600;
    text-align:center;
    padding:20px;
    border-bottom:1px solid rgba(255,255,255,0.3);
}
.sidebar nav a {
    display:flex;
    align-items:center;
    padding:15px 20px;
    color:#fff;
    text-decoration:none;
    transition:0.3s;
}
.sidebar nav a i { margin-right:15px; font-size:18px; }
.sidebar nav a:hover { background-color: rgba(255,255,255,0.1); padding-left:25px; }
.sidebar nav a.active { background-color:#4682b4; }

/* Main content */
.main-content {
    flex-grow:1;
    padding:20px 30px;
}

/* Header */
.header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}
.header h2 { color:#1e90ff; }
.header a.logout {
    text-decoration:none;
    padding:10px 20px;
    background-color:#1e90ff;
    color:#fff;
    border-radius:8px;
    transition:0.3s;
}
.header a.logout:hover { background-color:#4682b4; }

/* Add Driver Button */
.add-driver-btn {
    background:#28a745;
    color:#fff;
    padding:10px 20px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    margin-bottom:15px;
    font-weight:600;
    transition:0.3s;
}
.add-driver-btn:hover { background:#218838; }

/* Table card */
.card {
    background:#fff;
    border-radius:12px;
    padding:20px;
    box-shadow:0 6px 20px rgba(0,0,0,0.1);
    transition:all 0.3s ease;
}
.card:hover { transform:translateY(-5px); box-shadow:0 12px 30px rgba(0,0,0,0.15); }

table {
    width:100%;
    border-collapse:collapse;
}
table thead {
    background:#1e90ff;
    color:#fff;
}
table thead th {
    padding:12px;
    text-align:left;
}
table tbody tr {
    border-bottom:1px solid #ddd;
    transition:0.3s;
}
table tbody tr:hover { background:#f1f7ff; }
table tbody td { padding:12px; }

.btn-action {
    padding:6px 12px;
    border-radius:6px;
    color:#fff;
    text-decoration:none;
    margin-right:5px;
    transition:0.3s;
}
.btn-edit { background:#28a745; }
.btn-edit:hover { background:#218838; }
.btn-delete { background:#dc3545; }
.btn-delete:hover { background:#c82333; }

/* Modal */
.modal { display:none; position:fixed; z-index:999; left:0; top:0; width:100%; height:100%; overflow:auto; background:rgba(0,0,0,0.5); }
.modal-content { background:#fff; margin:10% auto; padding:30px; border-radius:12px; width:90%; max-width:500px; position:relative; }
.close { position:absolute; top:10px; right:15px; font-size:24px; font-weight:bold; color:#333; cursor:pointer; }

/* Responsive */
@media (max-width:768px) {
    .sidebar { width:60px; }
    .sidebar nav a span { display:none; }
    .main-content { padding:20px 15px; }
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">Bus Admin</div>
    <nav>
        <a href="dashboard.php"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a>
        <a href="manage-category.php"><i class="fa fa-bus"></i> <span>Manage Buses</span></a>
        <a href="driver management.php" class="active"><i class="fa fa-user"></i> <span>Manage Drivers</span></a>
        <a href="manage-pass.php"><i class="fa fa-file-alt"></i> <span>Manage Passes</span></a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Manage Drivers</h2>
        <a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Add Driver Button -->
    <button class="add-driver-btn" id="openModal"><i class="fa fa-plus"></i> Add New Driver</button>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Driver Name</th>
                    <th>Phone Number</th> 
                    <th>Creation Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tbldriver";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if($query->rowCount() > 0) {
                    foreach($results as $row) { ?>
                        <tr>
                            <td><?php echo htmlentities($cnt); ?></td>
                            <td><?php echo htmlentities($row->DriverName); ?></td>
                            <td><?php echo htmlentities($row->PhoneNumber); ?></td>
                            <td><?php echo htmlentities($row->CreatedDate); ?></td>
                            <td>
                                <a class="btn-action btn-edit" href="edit-driver.php?editid=<?php echo htmlentities($row->ID); ?>"><i class="fa fa-edit"></i> Edit</a>
                                <a class="btn-action btn-delete" href="driver management.php?delid=<?php echo htmlentities($row->ID); ?>" onclick="return confirm('Do you really want to delete?');"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php $cnt++; } } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="driverModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Add New Driver</h3>
        <form method="post">
            <div style="margin-bottom:15px;">
                <input type="text" name="drivername" placeholder="Driver Name" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc;">
            </div>
            <div style="margin-bottom:15px;">
                <input type="text" name="phonenumber" placeholder="Phone Number" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc;">
            </div>
            <button type="submit" name="add_driver" style="background:#1e90ff; color:#fff; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">Add Driver</button>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById("driverModal");
const btn = document.getElementById("openModal");
const span = document.getElementsByClassName("close")[0];

btn.onclick = function() { modal.style.display = "block"; }
span.onclick = function() { modal.style.display = "none"; }
window.onclick = function(event) { if(event.target == modal) modal.style.display = "none"; }
</script>

<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
</body>
</html>
<?php } ?>
