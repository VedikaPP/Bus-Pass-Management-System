<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['submit']))
{
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']); // hash password

    $sql = "SELECT Email FROM tbladmin WHERE Email=:email AND MobileNumber=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() > 0)
    {
        $update = "UPDATE tbladmin SET Password=:newpassword WHERE Email=:email AND MobileNumber=:mobile";
        $chngpwd = $dbh->prepare($update);
        $chngpwd->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd->execute();
        echo "<script>alert('Your password has been successfully changed');</script>";
    }
    else
    {
        echo "<script>alert('Invalid Email or Mobile number');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bus Pass Management System | Forgot Password</title>
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

        .forgot-card {
            background: rgba(255,255,255,0.95);
            width: 100%;
            max-width: 450px;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        .forgot-card h2 {
            color: #1e90ff;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .forgot-card input[type="email"],
        .forgot-card input[type="text"],
        .forgot-card input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: all 0.3s ease;
        }

        .forgot-card input:focus {
            border-color: #1e90ff;
            box-shadow: 0 0 8px rgba(30,144,255,0.3);
            outline: none;
        }

        .forgot-card .btn {
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

        .forgot-card .btn:hover {
            background: #4682b4;
            transform: translateY(-2px);
        }

        .forgot-card .login-link {
            margin-top: 15px;
            display: block;
            font-size: 14px;
        }

        .forgot-card .login-link a {
            color: #1e90ff;
            text-decoration: none;
            transition: 0.3s;
        }

        .forgot-card .login-link a:hover {
            text-decoration: underline;
        }
    </style>
    <script type="text/javascript">
        function valid() {
            if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
                alert("New Password and Confirm Password do not match!");
                document.chngpwd.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

    <div class="forgot-card">
        <h2>Reset Your Password</h2>
        <form name="chngpwd" method="post" onsubmit="return valid();">
            <input type="email" name="email" placeholder="Enter Your Email" required>
            <input type="text" name="mobile" placeholder="Enter Your Mobile Number" maxlength="10" pattern="[0-9]+" required>
            <input type="password" name="newpassword" placeholder="New Password" required>
            <input type="password" name="confirmpassword" placeholder="Confirm Password" required>
            <input type="submit" name="submit" value="Reset Password" class="btn">
            <span class="login-link"><a href="index.php">Back to Login</a></span>
        </form>
    </div>

</body>
</html>
