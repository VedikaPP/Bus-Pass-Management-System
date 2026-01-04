<?php 
include('includes/dbconnection.php');
session_start();
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bus Pass ID</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
body { 
    font-family: 'Roboto', sans-serif; 
    background: #f0f2f5; 
    padding: 40px 20px; 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    min-height: 100vh; 
}

/* Page Title */
h1 { 
    text-align: center; 
    color: #6f42c1; 
    margin-bottom: 40px; 
    font-weight: 700; 
    font-size: 36px; 
}

/* Search Form */
.search-form { 
    max-width: 500px; 
    width: 100%;
    display: flex; 
    gap: 15px; 
    margin-bottom: 40px; 
}
.search-form input { 
    flex: 1; 
    padding: 15px 20px; 
    border-radius: 12px; 
    border: 1px solid #ccc; 
    font-size: 18px;
}
.search-form button { 
    padding: 15px 25px; 
    border-radius: 12px; 
    border: none; 
    background-color: #6f42c1; 
    color: #fff; 
    font-size: 18px;
    cursor: pointer; 
    transition: 0.3s; 
}
.search-form button:hover { 
    background-color: #5632a8; 
}

/* Pass Card */
.pass-card { 
    max-width: 500px; 
    width: 100%;
    background: linear-gradient(145deg, #6f42c1, #8f63d2); 
    color: #fff; 
    margin: 30px auto; 
    border-radius: 20px; 
    box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
    overflow: hidden; 
    padding: 30px 25px; 
    text-align: center; 
}
.pass-card img { 
    width: 120px; 
    height: 120px; 
    border-radius: 50%; 
    border: 4px solid #fff; 
    margin-bottom: 15px; 
    object-fit: cover; 
}
.pass-card h2 { 
    font-size: 26px; 
    margin: 10px 0; 
    font-weight: 700; 
}
.pass-card p { 
    margin: 6px 0; 
    font-size: 16px; 
    font-weight: 500; 
}
.pass-footer { 
    margin-top: 20px; 
    font-size: 14px; 
    color: rgba(255,255,255,0.9); 
}
.no-result { 
    text-align: center; 
    color: #dc3545; 
    font-weight: 500; 
    font-size: 18px;
    margin-top: 20px; 
}

/* Centered Buttons */
.button-wrapper {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    width: 100%;
    margin-top: 30px;
}
.btn-back, .btn-print { 
    padding: 12px 30px; 
    background-color: #6f42c1; 
    color: #fff; 
    text-decoration: none; 
    border-radius: 30px; 
    transition: 0.3s; 
    font-size: 18px;
    text-align: center; 
    cursor: pointer;
}
.btn-back:hover, .btn-print:hover { 
    background-color: #5632a8; 
}

/* Print Specific Button */
.btn-print {
    background-color: #28a745;
}
.btn-print:hover {
    background-color: #218838;
}

/* Responsive */
@media(max-width: 600px) { 
    .pass-card { max-width: 90%; padding: 25px 20px; }
    .search-form { max-width: 90%; gap: 10px; }
    .search-form input, .search-form button { font-size: 16px; padding: 12px 15px; }
    .pass-card h2 { font-size: 22px; }
    .pass-card p { font-size: 15px; }
    .btn-back, .btn-print { font-size: 16px; padding: 10px 25px; }
}
</style>

<script>
function printPass() {
    // Print only the pass card content
    var passContent = document.querySelector('.pass-card');
    if(passContent){
        var newWin = window.open('', '_blank');
        newWin.document.write('<html><head><title>Print Bus Pass</title>');
        newWin.document.write('<style>body{font-family:Roboto,sans-serif;text-align:center;margin:50px;} .pass-card{background:#6f42c1;color:#fff;padding:30px 25px;border-radius:20px;display:inline-block;}</style>');
        newWin.document.write('</head><body>');
        newWin.document.write(passContent.outerHTML);
        newWin.document.write('</body></html>');
        newWin.document.close();
        newWin.print();
    }
}
</script>

</head>

<body>

<h1>Bus Pass ID</h1>

<form class="search-form" method="post">
    <input type="text" name="searchdata" placeholder="Enter your PRN Number" required>
    <button type="submit" name="search">Search</button>
</form>

<?php
if (isset($_POST['search'])) {
    $sdata = trim($_POST['searchdata']);
    if (!empty($sdata)) {
        try {
            $sql = "SELECT * FROM tblpass1 WHERE prn_number = :sdata LIMIT 1";
            $query = $dbh->prepare($sql);
            $query->bindParam(':sdata', $sdata, PDO::PARAM_STR);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_OBJ);

            if ($row) {
                $profileImage = !empty($row->ProfileImage) ? 'admin/images/' . htmlentities($row->ProfileImage) : '';
                $fullName = htmlentities($row->FullName ?? 'Unknown');
                $prn = htmlentities($row->prn_number ?? '-');
                $branch = htmlentities($row->Branch ?? '-');
                $category = htmlentities($row->Category ?? 'N/A');
                $pendingFees = isset($row->pending_result) ? htmlentities($row->pending_result) : '0';
                $fromDate = htmlentities($row->FromDate ?? '-');

                echo '
                <div class="pass-card">
                    <img src="' . $profileImage . '" alt="Profile Image">
                    <h2>' . $fullName . '</h2>
                    <p><strong>PRN:</strong> ' . $prn . '</p>
                    <p><strong>Branch:</strong> ' . $branch . '</p>
                    <p><strong>Category:</strong> ' . $category . '</p>
                    <p><strong>Pending Fees:</strong> ₹' . $pendingFees . '</p>
                    <p><strong>Date:</strong> ' . $fromDate . '</p>
                    <div class="pass-footer">
                        <small>Bus Pass - Valid ID</small>
                    </div>
                </div>';

                // Buttons wrapper: Go Back + Print Pass
                echo '<div class="button-wrapper">
                        <a href="student/dashboard.php" class="btn-back">← Go Back</a>
                        <button class="btn-print" onclick="printPass()">🖨️ Print Pass</button>
                      </div>';

            } else {
                echo '<p class="no-result">No pass found with the provided PRN Number.</p>';
            }
        } catch (Exception $e) {
            echo '<p class="no-result">Error: ' . $e->getMessage() . '</p>';
        }
    } else {
        echo '<p class="no-result">Please enter a valid PRN Number.</p>';
    }
}
?>

</body>
</html>
