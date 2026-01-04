<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bus Pass Management System | Pass Details</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
<link href="assets/css/style.css" rel="stylesheet" />
<link href="assets/css/main-style.css" rel="stylesheet" />

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

.btn-back, .btn-print { padding:8px 20px; border-radius:6px; color:#fff; border:none; cursor:pointer; margin-bottom:15px; transition:0.3s; }
.btn-back { background:#6c757d; }
.btn-back:hover { background:#5a6268; }
.btn-print { background:#28a745; }
.btn-print:hover { background:#218838; }

.pass-table { width:100%; border-collapse:collapse; }
.pass-table th { background:#1e90ff; color:#fff; padding:12px; text-align:left; }
.pass-table td { padding:12px; border-bottom:1px solid #ddd; vertical-align:middle; }
.pass-table tr:hover { background:#f1f7ff; }
.pass-table img { border-radius:5px; }

.form-group { margin-bottom:15px; }

@media (max-width:768px){ .sidebar{width:60px;} .sidebar nav a span{display:none;} .main-content{padding:20px 15px;} }
</style>

<script type="text/javascript">
function PrintDiv() {
    var divToPrint = document.getElementById('divToPrint');
    var popupWin = window.open('', '_blank', 'width=900,height=700');
    popupWin.document.open();
    popupWin.document.write('<html><head><title>Print Pass</title></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
    popupWin.document.close();
}
</script>
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
        <h2>Pass Details</h2>
        <button class="logout" onclick="window.location.href='logout.php'"><i class="fa fa-sign-out-alt"></i> Logout</button>
    </div>

    <div class="card">
        <button class="btn-back" onclick="window.location.href='manage-pass.php';">
            <i class="fa fa-arrow-left"></i> Go Back
        </button>

        <div id="divToPrint">
            <?php
            $prn_number = isset($_GET['prn_number']) ? trim($_GET['prn_number']) : '';

            if (empty($prn_number)) { ?>
                <form method="GET" action="">
                    <div class="form-group">
                        <label for="prn_number">Enter PRN Number:</label>
                        <input type="text" name="prn_number" id="prn_number" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-add"><i class="fa fa-search"></i> Search</button>
                </form>
            <?php } else {
                try {
                    $sql = "SELECT PassNumber, FullName, ProfileImage, ContactNumber, prn_number, 
                                   Category, Branch, Degree, Source, Destination, FromDate, ToDate, 
                                   Cost, Payment_result, Pending_result
                            FROM tblpass1 
                            WHERE prn_number = :prn_number";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':prn_number', $prn_number, PDO::PARAM_STR);
                    $query->execute();
                    $result = $query->fetch(PDO::FETCH_ASSOC);

                    if ($result) { ?>
                        <table class="pass-table">
                            <tr>
                                <th colspan="4" style="text-align:center; font-size:20px;">Pass Number: <?php echo htmlspecialchars($result['PassNumber']); ?></th>
                            </tr>
                            <tr>
                                <th>Full Name</th>
                                <td colspan="3"><?php echo htmlspecialchars($result['FullName']); ?></td>
                            </tr>
                            <tr>
                                <th>Photo</th>
                                <td colspan="3">
                                    <img src="images/<?php echo htmlspecialchars($result['ProfileImage']); ?>" width="120" height="120" alt="Profile">
                                </td>
                            </tr>
                            <tr>
                                <th>PRN Number</th>
                                <td><?php echo htmlspecialchars($result['prn_number']); ?></td>
                                <th>Contact Number</th>
                                <td><?php echo htmlspecialchars($result['ContactNumber']); ?></td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td><?php echo htmlspecialchars($result['Category']); ?></td>
                                <th>Branch</th>
                                <td><?php echo htmlspecialchars($result['Branch']); ?></td>
                            </tr>
                            <tr>
                                <th>Degree</th>
                                <td><?php echo htmlspecialchars($result['Degree']); ?></td>
                                <th>Source</th>
                                <td><?php echo htmlspecialchars($result['Source']); ?></td>
                            </tr>
                            <tr>
                                <th>Destination</th>
                                <td><?php echo htmlspecialchars($result['Destination']); ?></td>
                                <th>From Date</th>
                                <td><?php echo htmlspecialchars($result['FromDate']); ?></td>
                            </tr>
                            <tr>
                                <th>To Date</th>
                                <td><?php echo htmlspecialchars($result['ToDate']); ?></td>
                                <th>Cost</th>
                                <td><?php echo htmlspecialchars($result['Cost']); ?></td>
                            </tr>
                            <tr>
                                <th>Payment Made</th>
                                <td><?php echo htmlspecialchars($result['Payment_result']); ?></td>
                                <th>Pending Amount</th>
                                <td><?php echo htmlspecialchars($result['Pending_result']); ?></td>
                            </tr>
                        </table>
                        <div class="text-center" style="margin-top:15px;">
                            <button class="btn-print" onclick="PrintDiv();"><i class="fa fa-print"></i> Print</button>
                        </div>
                    <?php } else {
                        echo "<p class='text-danger'>No record found for PRN Number: " . htmlspecialchars($prn_number) . "</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p class='text-danger'>Error: " . $e->getMessage() . "</p>";
                }
            }
            ?>
        </div>
    </div>
</div>

<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
</body>
</html>
