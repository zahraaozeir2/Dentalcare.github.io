<?php
include "config/db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $error = "This email is already registered. Please sign in instead.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: auth/login.php");
            exit;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>

    <style>
        body{
            margin:0;
            font-family:Arial,sans-serif;
            background:linear-gradient(135deg,#eefaf8,#f3f6f8);
            color:#06284a;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .card{
            width:380px;
            background:white;
            border-radius:22px;
            padding:32px;
            box-shadow:0 12px 30px rgba(0,0,0,.10);
        }

        .logo{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:25px;
        }

        .logo-icon{
            width:55px;
            height:55px;
            border-radius:16px;
            background:#2fb8ad;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:28px;
        }

        .logo-text{
            font-size:30px;
            font-weight:bold;
        }

        h2{
            margin:0 0 6px;
            font-size:26px;
        }

        .subtitle{
            color:#6f8199;
            margin-bottom:25px;
        }

        label{
            display:block;
            font-size:14px;
            font-weight:bold;
            margin-bottom:7px;
        }

        input{
            width:100%;
            padding:13px;
            border:1px solid #dce5ec;
            border-radius:12px;
            margin-bottom:16px;
            font-size:14px;
            box-sizing:border-box;
            background:#f8fbfc;
        }

        input:focus{
            outline:none;
            border-color:#2fb8ad;
            box-shadow:0 0 0 4px rgba(47,184,173,.15);
            background:white;
        }

        .btn{
            width:100%;
            background:#2fb8ad;
            color:white;
            border:none;
            border-radius:12px;
            padding:13px;
            font-size:15px;
            font-weight:bold;
            cursor:pointer;
        }

        .btn:hover{
            background:#15998f;
        }

        .message{
            padding:12px;
            border-radius:10px;
            margin-bottom:15px;
            font-size:14px;
            font-weight:bold;
        }

        .error{
            background:#ffecec;
            color:#dc3545;
        }

        .links{
            margin-top:22px;
            text-align:center;
            color:#6f8199;
            font-size:14px;
        }

        .links a{
            color:#2fb8ad;
            font-weight:bold;
            text-decoration:none;
        }
    </style>
</head>

<body>

<div class="card">

    <div class="logo">
        <div class="logo-icon">🩺</div>
        <div class="logo-text">DentaCare</div>
    </div>

    <h2>Create Account</h2>
    <div class="subtitle">Register a new clinic user</div>

    <?php if($error != "") { ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="full_name" placeholder="Enter full name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <button type="submit" class="btn">Sign Up</button>
    </form>

    <div class="links">
        Already have an account? <a href="auth/login.php">Sign in</a>
    </div>

</div>

</body>
</html>
