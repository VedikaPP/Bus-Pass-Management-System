<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $drivername = $_POST['drivername'];
    $eid = $_GET['editid'];

    // Checking if the driver name already exists
    $ret = "SELECT DriverName FROM tbldriver WHERE DriverName=:drivername";
    $query = $dbh->prepare($ret);
    $query->bindParam(':drivername', $drivername, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if ($query->rowCount() == 0) {
      // If no existing driver name found, update the driver info
      $sql = "UPDATE tbldriver SET DriverName=:drivername WHERE ID=:eid";
      $query = $dbh->prepare($sql);
      $query->bindParam(':drivername', $drivername, PDO::PARAM_STR);
      $query->bindParam(':eid', $eid, PDO::PARAM_STR);
      $query->execute();
      echo '<script>alert("Driver details have been updated")</script>';
    } else {
      echo "<script>alert('Driver Name already exists. Please try again');</script>";
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bus Pass Management System | Update Driver</title>
    <!-- Core CSS -->
    <link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/css/main-style.css" rel="stylesheet" />
</head>

<body>
    <div id="wrapper">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/sidebar.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Update Driver</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form method="post" enctype="multipart/form-data">
                                        <?php
                                        $eid = $_GET['editid'];
                                        $sql = "SELECT * FROM tbldriver WHERE ID=:eid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                <div class="form-group">
                                                    <label for="drivername">Driver Name</label>
                                                    <input type="text" name="drivername" value="<?php echo htmlentities($row->DriverName); ?>" class="form-control" required>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                        <p style="text-align:center;">
                                            <button type="submit" class="btn btn-primary" name="submit" id="submit">Update</button>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Form Elements -->
                </div>
            </div>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="assets/plugins/jquery-1.10.2.js"></script>
    <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="assets/plugins/pace/pace.js"></script>
    <script src="assets/scripts/siminta.js"></script>
</body>
</html>

<?php } ?>
