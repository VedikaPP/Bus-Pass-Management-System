<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php'); // DB connection

if(isset($_POST['login'])) 
{
    $username = $_POST['username'];
    $password = md5($_POST['password']); // hash to match DB

    $sql = "SELECT ID FROM tbladmin WHERE UserName=:username AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    
    if($query->rowCount() > 0)
    {
        $result = $query->fetch(PDO::FETCH_OBJ);
        $_SESSION['bpmsaid'] = $result->ID;
        $_SESSION['login'] = $username;

        // Remember Me
        if(!empty($_POST["remember"])) {
            setcookie("user_login", $_POST["username"], time() + (10*365*24*60*60));
            setcookie("userpassword", $_POST["password"], time() + (10*365*24*60*60));
        } else {
            setcookie("user_login", "", time() - 3600);
            setcookie("userpassword", "", time() - 3600);
        }

        echo "<script>document.location='dashboard.php';</script>";
        exit;
    }
    else {
        echo "<script>alert('Invalid Username or Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bus Pass Management System | Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="assets/plugins/bootstrap/bootstrap.css" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            height: 100vh;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: slideIn 1s ease forwards;
        }

        @keyframes slideIn {
            0% { opacity: 0; transform: translateY(-50px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .login-card h2 {
            color: #1e90ff;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .login-card input[type="text"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: all 0.3s ease;
        }

        .login-card input:focus {
            border-color: #1e90ff;
            box-shadow: 0 0 8px rgba(30,144,255,0.3);
            outline: none;
        }

        .login-card .btn {
            width: 100%;
            padding: 15px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            background: #1e90ff;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-card .btn:hover {
            background: #4682b4;
            transform: translateY(-2px);
        }

        .login-card .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
            font-size: 14px;
        }

        .login-card .remember-forgot label {
            cursor: pointer;
        }

        .login-card .remember-forgot a {
            color: #1e90ff;
            text-decoration: none;
            transition: 0.3s;
        }

        .login-card .remember-forgot a:hover {
            text-decoration: underline;
        }

        /* Back button styling */
        .login-card .back-btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: #f0f0f0;
            color: #1e90ff;
            border: 1px solid #1e90ff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-card .back-btn:hover {
            background: #1e90ff;
            color: #fff;
        }

        @media (max-width: 480px) {
            .login-card { padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Admin Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required 
                   value="<?php if(isset($_COOKIE['user_login'])) echo $_COOKIE['user_login']; ?>">
            <input type="password" name="password" placeholder="Password" required 
                   value="<?php if(isset($_COOKIE['userpassword'])) echo $_COOKIE['userpassword']; ?>">
            
            <div class="remember-forgot">
                <label><input type="checkbox" name="remember" <?php if(isset($_COOKIE['user_login'])) echo "checked"; ?>> Remember Me</label>
                <a href="forgot-password.php">Forgot Password?</a>
            </div>

            <input type="submit" name="login" value="Login" class="btn">
        </form>

        <!-- Back Button -->
        <a href="../index.html" class="back-btn">Back</a>
    </div>
</body>
</html>
