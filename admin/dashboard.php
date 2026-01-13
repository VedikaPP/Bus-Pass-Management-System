<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
    header('location:logout.php');
} else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bus Pass Management System | Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0; padding: 0;
    background: #f4f7f9;
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 240px;
    background-color: #1e90ff;
    color: #fff;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    transition: 0.3s;
}
.sidebar .logo {
    font-size: 24px;
    font-weight: 600;
    text-align: center;
    padding: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.3);
}
.sidebar nav a {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    color: #fff;
    text-decoration: none;
    transition: 0.3s;
}
.sidebar nav a i {
    margin-right: 15px;
    font-size: 18px;
}
.sidebar nav a:hover {
    background-color: rgba(255,255,255,0.1);
    padding-left: 25px;
}
.sidebar nav a.active {
    background-color: #4682b4;
}

/* Main content */
.main-content {
    flex-grow: 1;
    padding: 20px 30px;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.header h2 {
    color: #1e90ff;
}
.header a.logout {
    text-decoration: none;
    padding: 10px 20px;
    background-color: #1e90ff;
    color: #fff;
    border-radius: 8px;
    transition: 0.3s;
}
.header a.logout:hover {
    background-color: #4682b4;
}

/* Dashboard cards */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px,1fr));
    gap: 25px;
}
.card {
    background-color: #fff;
    border-radius: 12px;
    padding: 30px 20px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
}
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}
.card i {
    font-size: 50px;
    margin-bottom: 15px;
    color: #1e90ff;
}
.card b {
    display: block;
    font-size: 32px;
    color: #333;
    margin-bottom: 5px;
}
.card a {
    display: inline-block;
    margin-top: 10px;
    text-decoration: none;
    color: #1e90ff;
    font-weight: 500;
}
.card a:hover {
    color: #4682b4;
}

/* Colored border on cards */
.card.bus { border-top: 5px solid #ff6b6b; }
.card.driver { border-top: 5px solid #6bc1ff; }
.card.pass { border-top: 5px solid #28a745; }

/* Responsive */
@media (max-width: 768px) {
    .sidebar { width: 60px; }
    .sidebar nav a span { display: none; }
    .main-content { padding: 20px 15px; }
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">Bus Admin</div>
    <nav>
        <a href="dashboard.php" class="active"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a>
        <a href="manage-category.php"><i class="fa fa-bus"></i> <span>Manage Buses</span></a>
        <a href="driver management.php"><i class="fa fa-user"></i> <span>Manage Drivers</span></a>
        <a href="manage-pass.php"><i class="fa fa-file-alt"></i> <span>Manage Passes</span></a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Welcome Admin</h2>
        <a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="dashboard-cards">
        <!-- Total Buses -->
        <?php 
        $sql = "SELECT ID FROM tblcategory";
        $query = $dbh->prepare($sql);
        $query->execute();
        $totalbus = $query->rowCount();
        ?>
        <div class="card bus">
            <i class="fa fa-bus"></i>
            <b class="counter"><?php echo htmlentities($totalbus); ?></b>
            <a href="manage-category.php">Total Buses</a>
        </div>

        <!-- Total Drivers -->
        <?php 
        $sql = "SELECT ID FROM tbldriver";
        $query = $dbh->prepare($sql);
        $query->execute();
        $totaldriver = $query->rowCount();
        ?>
        <div class="card driver">
            <i class="fa fa-user"></i>
            <b class="counter"><?php echo htmlentities($totaldriver); ?></b>
            <a href="driver management.php">Total Drivers</a>
        </div>

        <!-- Total Pass -->
        <?php 
        $sql = "SELECT ID FROM tblpass";
        $query = $dbh->prepare($sql);
        $query->execute();
        $totalpass = $query->rowCount();
        ?>
        <div class="card pass">
            <i class="fa fa-file-alt"></i>
            <b class="counter"><?php echo htmlentities($totalpass); ?></b>
            <a href="manage-pass.php">Total Pass</a>
        </div>
    </div>
</div>

<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script>
// Smooth counter animation
document.querySelectorAll('.counter').forEach(counter => {
    const target = +counter.innerText;
    let count = 0;
    const increment = Math.ceil(target / 100);
    const animate = () => {
        if (count < target) {
            count += increment;
            counter.innerText = count > target ? target : count;
            requestAnimationFrame(animate);
        }
    };
    animate();
});
</script>
</body>
</html>
<?php } ?>
