<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1);
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Get the PRN number from the URL
$prn_number = isset($_GET['prn_number']) ? trim($_GET['prn_number']) : '';

if (empty($prn_number)) {
    echo "<p style='color:red;'>No PRN number provided in the URL.</p>";
    exit;
}

try {
    // Fetch the record from the database using PRN number
    $sql = "SELECT PassNumber, FullName, ProfileImage, ContactNumber, prn_number, 
                   Category, Branch, Degree, Source, Destination, FromDate, ToDate, Cost, Pending 
            FROM tblpass1 WHERE prn_number = :prn_number";
    $query = $dbh->prepare($sql);
    $query->bindParam(':prn_number', $prn_number, PDO::PARAM_STR);
    $query->execute();

    // Check if a record was found
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pass Details</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .pass-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="pass-container">
            <h2 class="text-center">Pass Details</h2>
            <div class="text-center">
                <?php if (!empty($result['ProfileImage'])) { ?>
                    <img src="uploads/<?php echo htmlentities($result['ProfileImage']); ?>" class="profile-img" alt="Profile Image">
                <?php } else { ?>
                    <p>No Profile Image Available</p>
                <?php } ?>
            </div>
            <table class="table table-bordered">
                <tr>
                    <th>Pass Number</th>
                    <td><?php echo htmlentities($result['PassNumber']); ?></td>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <td><?php echo htmlentities($result['FullName']); ?></td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td><?php echo htmlentities($result['ContactNumber']); ?></td>
                </tr>
                <tr>
                    <th>PRN Number</th>
                    <td><?php echo htmlentities($result['prn_number']); ?></td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td><?php echo htmlentities($result['Category']); ?></td>
                </tr>
                <tr>
                    <th>Branch</th>
                    <td><?php echo htmlentities($result['Branch']); ?></td>
                </tr>
                <tr>
                    <th>Degree</th>
                    <td><?php echo htmlentities($result['Degree']); ?></td>
                </tr>
                <tr>
                    <th>Source</th>
                    <td><?php echo htmlentities($result['Source']); ?></td>
                </tr>
                <tr>
                    <th>Destination</th>
                    <td><?php echo htmlentities($result['Destination']); ?></td>
                </tr>
                <tr>
                    <th>From Date</th>
                    <td><?php echo htmlentities($result['FromDate']); ?></td>
                </tr>
                <tr>
                    <th>To Date</th>
                    <td><?php echo htmlentities($result['ToDate']); ?></td>
                </tr>
                <tr>
                    <th>Cost</th>
                    <td><?php echo htmlentities($result['Cost']); ?></td>
                </tr>
                <tr>
                    <th>Pending</th>
                    <td><?php echo htmlentities($result['Pending']); ?></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

<?php
    } else {
        echo "<p style='color:red;'>No record found for PRN Number: " . htmlentities($prn_number) . "</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
