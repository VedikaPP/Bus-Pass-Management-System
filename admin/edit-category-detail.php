<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(strlen($_SESSION['bpmsaid']==0)){
    header('location:logout.php');
} else {

if(isset($_GET['editid'])){
    $cid=intval($_GET['editid']);
}

if(isset($_POST['update_category'])){
    $name = $_POST['categoryname'];
    $sql="UPDATE tblcategory SET CategoryName=:name WHERE ID=:cid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':name',$name,PDO::PARAM_STR);
    $query->bindParam(':cid',$cid,PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Category updated successfully');</script>";
    echo "<script>window.location.href='manage-category.php';</script>";
}

// Fetch current category details
$sql="SELECT * FROM tblcategory WHERE ID=:cid";
$query=$dbh->prepare($sql);
$query->bindParam(':cid',$cid,PDO::PARAM_STR);
$query->execute();
$result=$query->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bus Pass Management System | Edit Bus Category</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
    padding:40px 30px;
}

/* Header */
.header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
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

/* Card */
.card {
    background:#fff;
    border-radius:12px;
    padding:30px;
    box-shadow:0 6px 20px rgba(0,0,0,0.1);
    max-width:600px;
    margin:auto;
    transition:all 0.3s ease;
}
.card:hover { transform:translateY(-5px); box-shadow:0 12px 30px rgba(0,0,0,0.15); }

/* Form */
.card form input {
    width:100%;
    padding:12px 15px;
    border-radius:8px;
    border:1px solid #ccc;
    margin-bottom:20px;
    font-size:16px;
}
.card form button {
    padding:12px 25px;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}
.btn-update { background:#28a745; color:#fff; }
.btn-update:hover { background:#218838; }
.btn-back { background:#6f42c1; color:#fff; margin-left:10px; }
.btn-back:hover { background:#5632a8; }

/* Button wrapper */
.button-wrapper { display:flex; justify-content:flex-start; }

/* Responsive */
@media (max-width:768px){
    .sidebar{width:60px;}
    .sidebar nav a span{display:none;}
    .main-content{padding:20px 15px;}
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">Bus Admin</div>
    <nav>
        <a href="dashboard.php"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a>
        <a href="manage-category.php" class="active"><i class="fa fa-bus"></i> <span>Manage Buses</span></a>
        <a href="driver management.php"><i class="fa fa-user"></i> <span>Manage Drivers</span></a>
        <a href="manage-pass.php"><i class="fa fa-file-alt"></i> <span>Manage Passes</span></a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Edit Bus Category</h2>
        <a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="card">
        <form method="post">
            <label for="categoryname"><strong>Category Name</strong></label>
            <input type="text" name="categoryname" id="categoryname" value="<?php echo htmlentities($result->CategoryName); ?>" required>
            
            <div class="button-wrapper">
                <button type="submit" name="update_category" class="btn-update"><i class="fa fa-save"></i> Update Category</button>
                <a href="manage-category.php" class="btn-back"><i class="fa fa-arrow-left"></i> Go Back</a>
            </div>
        </form>
    </div>
</div>

<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>

</body>
</html>

<?php } ?>
