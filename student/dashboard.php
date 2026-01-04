<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

/* DB CONNECTION */
include('../includes/dbconnection.php');

/* FETCH STUDENT INFO */
$studentId = $_SESSION['student_id'];
$sql = "SELECT fullname FROM tblstudents WHERE id=:sid LIMIT 1";
$q = $dbh->prepare($sql);
$q->bindParam(':sid', $studentId, PDO::PARAM_INT);
$q->execute();
$student = $q->fetch(PDO::FETCH_OBJ);

$fullname = $student->fullname ?? 'Student';
$initial = strtoupper(substr($fullname, 0, 1)); // First letter
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bus Pass | Student Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: #f4f7f9;
    display: flex;
    min-height: 100vh;
}

/* ===== Sidebar ===== */
.sidebar {
    width: 230px;
    background: linear-gradient(180deg, #ff6a00, #ff8c42);
    color: #fff;
    flex-shrink: 0;
    transition: 0.3s;
}
.sidebar .logo {
    text-align: center;
    font-size: 22px;
    font-weight: 600;
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
    font-size: 18px;
    margin-right: 15px;
}
.sidebar nav a:hover,
.sidebar nav a.active {
    background: rgba(255,255,255,0.15);
    padding-left: 25px;
}

/* ===== Main Content ===== */
.main-content {
    flex-grow: 1;
    padding: 25px 30px;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.header h2 {
    color: #ff6a00;
    font-weight: 600;
}

/* ===== Profile Dropdown ===== */
.profile-dropdown {
    position: relative;
    cursor: pointer;
}
.profile-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff6a00, #ff8c42);
    color: #fff;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s, box-shadow 0.2s;
}
.profile-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Dropdown menu */
.dropdown-menu-custom {
    position: absolute;
    top: 50px;
    right: 0;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    min-width: 180px;
    display: none;
    z-index: 1000;
}
.dropdown-menu-custom a, 
.dropdown-menu-custom label {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.2s;
}
.dropdown-menu-custom a:hover,
.dropdown-menu-custom label:hover {
    background: #f4f4f4;
}
.dropdown-menu-custom hr {
    margin: 5px 0;
    border: none;
    border-top: 1px solid #eee;
}

/* Toggle Switch */
.switch {
  position: relative;
  display: inline-block;
  width: 36px;
  height: 20px;
}
.switch input { display:none; }
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #ccc;
  border-radius: 34px;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "";
  height: 14px;
  width: 14px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  border-radius: 50%;
  transition: .4s;
}
input:checked + .slider {
  background-color: #ff6a00;
}
input:checked + .slider:before {
  transform: translateX(16px);
}

/* ===== Dashboard Cards ===== */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
    gap: 25px;
}

.card-box {
    background: #fff;
    border-radius: 14px;
    padding: 30px 20px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    transition: 0.3s;
    position: relative;
}
.card-box:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}

.card-box i {
    font-size: 48px;
    margin-bottom: 15px;
}

/* Card Colors */
.card-newpass i { color: #ff6b6b; }
.card-location i { color: #1e90ff; }
.card-bus i { color: #28a745; }
.card-validity i { color: #6f42c1; }

.card-box h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
}

.card-box a {
    text-decoration: none;
    font-weight: 500;
    color: #ff6a00;
}
.card-box a:hover {
    color: #e85d00;
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
    .sidebar {
        width: 60px;
    }
    .sidebar nav a span {
        display: none;
    }
    .main-content {
        padding: 20px 15px;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const profile = document.querySelector('.profile-dropdown');
    const menu = document.querySelector('.dropdown-menu-custom');

    profile.addEventListener('click', function(e) {
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function(e) {
        if (!profile.contains(e.target)) {
            menu.style.display = 'none';
        }
    });
});
</script>

</head>
<body>

<!-- ===== Sidebar ===== -->
<div class="sidebar">
    <div class="logo">Pass</div>
    <nav>
        <a href="student_dashboard.php" class="active">
            <i class="fa fa-home"></i> <span>Dashboard</span>
        </a>
        <a href="../pass.php">
            <i class="fa fa-id-card"></i> <span>View My Pass</span>
        </a>
        <a href="https://www.google.com/maps/place/Dnyanshree+Institute+of+Engineering+and+Technology/@17.6483915,73.9213757,17z/data=!3m1!4b1!4m6!3m5!1s0x3bc23c106163b291:0xb12ae7cee0fc091a!8m2!3d17.6483915!4d73.9213757!16s%2Fg%2F113hjfc58?entry=ttu&g_ep=EgoyMDI1MTIwOS4wIKXMDSoKLDEwMDc5MjA2N0gBUAM%3D">
            <i class="fa fa-location-dot"></i> <span>Bus Location</span>
        </a>
        <a href="../routeindex.html">
            <i class="fa fa-bus"></i> <span>Bus Details</span>
        </a>
        <a href="pass_validity.php">
            <i class="fa fa-calendar-check"></i> <span>Pass Validity</span>
        </a>
        <a href="../index.html">
            <i class="fa fa-sign-out-alt"></i> <span>Logout</span>
        </a>
    </nav>
</div>

<!-- ===== Main Content ===== -->
<div class="main-content">
    <div class="header">
        <h2>Welcome <?php echo htmlentities($fullname); ?></h2>
        
        <!-- Profile Dropdown -->
        <div class="profile-dropdown">
            <div class="profile-circle"><?php echo $initial; ?></div>
            <div class="dropdown-menu-custom">
                <a href="#">Profile: <?php echo htmlentities($fullname); ?></a>
                <a href="change_password.php">Change Password</a>
                <label>Dark Mode 
                    <span class="switch">
                        <input type="checkbox" id="darkModeToggle">
                        <span class="slider"></span>
                    </span>
                </label>
                <hr>
                <a href="../index.html">Logout</a>
            </div>
        </div>
    </div>

    <div class="dashboard-cards">

        <div class="card-box card-newpass">
            <i class="fa fa-id-card"></i>
            <h4>View My Pass</h4>
            <a href="../pass.php">View Pass</a>
        </div>

        <div class="card-box card-location">
            <i class="fa fa-location-dot"></i>
            <h4>Live Bus Location</h4>
            <a href="https://www.google.com/maps/place/Dnyanshree+Institute+of+Engineering+and+Technology/@17.6483915,73.9213757,17z/data=!3m1!4b1!4m6!3m5!1s0x3bc23c106163b291:0xb12ae7cee0fc091a!8m2!3d17.6483915!4d73.9213757!16s%2Fg%2F113hjfc58?entry=ttu&g_ep=EgoyMDI1MTIwOS4wIKXMDSoKLDEwMDc5MjA2N0gBUAM%3D">View Location</a>
        </div>

        <div class="card-box card-bus">
            <i class="fa fa-bus"></i>
            <h4>Bus Details</h4>
            <a href="../routeindex.html">View Details</a>
        </div>

        <div class="card-box card-validity">
            <i class="fa fa-calendar-check"></i>
            <h4>Pass Validity</h4>
            <a href="pass_validity.php">Check Validity</a>
        </div>

    </div>
</div>

</body>
</html>
