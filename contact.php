<?php
include('includes/dbconnection.php');
session_start();
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $sql = "INSERT INTO tblcontact (Name, Email, Message) VALUES (:name, :email, :message)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':name', $name);
            $query->bindParam(':email', $email);
            $query->bindParam(':message', $message);
            $query->execute();
            $success = true;
        } catch (PDOException $e) {
            $error = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | DIET Satara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Icons -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="contact.css" rel="stylesheet">
</head>

<body>

<!-- Topbar -->
<div class="topbar">
    <div class="container d-flex justify-content-between">
        <div>
            <i class="fas fa-map-marker-alt"></i> Satara, Maharashtra
            <i class="fas fa-phone ms-3"></i> +086000 09009
            <i class="fas fa-envelope ms-3"></i> rwmctsatara@dnyanshree.edu.in
        </div>
        <div>
            <i class="fab fa-facebook-f"></i>
            <i class="fab fa-instagram ms-3"></i>
            <i class="fab fa-linkedin-in ms-3"></i>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">DIET, Satara</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="nav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="about.html" class="nav-link">About</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link active">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="row g-5 align-items-center">

            <!-- Left Info -->
            <div class="col-lg-4 contact-info">
                <h3>Contact Information</h3>

                <div class="info-box">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Sonavadi-Gajavadi, Sajjangad Road,<br>Satara – 415013</p>
                </div>

                <div class="info-box">
                    <i class="fas fa-phone"></i>
                    <p>+91 7498612139<br>+91 9702666634</p>
                </div>

                <div class="info-box">
                    <i class="fas fa-envelope"></i>
                    <p>diet@gmail.com</p>
                </div>
            </div>

            <!-- Right Form -->
            <div class="col-lg-8">
                <div class="contact-form shadow">

                    <h3>Send us a message</h3>
                    <p>If you have any questions, feel free to reach out.</p>

                    <?php if (!empty($success)) { ?>
                        <div class="alert alert-success">Message sent successfully!</div>
                    <?php } elseif (!empty($error)) { ?>
                        <div class="alert alert-danger">Something went wrong.</div>
                    <?php } ?>

                    <form method="POST">
                        <input type="text" name="name" placeholder="Your Name" required>
                        <input type="email" name="email" placeholder="Your Email" required>
                        <textarea name="message" placeholder="Your Message" required></textarea>
                        <button type="submit">Send Message</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

<footer class="footer">
    <p>&copy; 2024 DIET Bus Transport System</p>
</footer>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
