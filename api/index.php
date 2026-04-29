<?php
include 'includes/auth_check.php';
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$total_patients = 0;
$total_appointments = 0;
$today_appointments = 0;
$total_visits = 0;
$total_invoices = 0;

$result = $conn->query("SELECT COUNT(*) AS total FROM patients");
if ($result) $total_patients = $result->fetch_assoc()['total'] ?? 0;

$result = $conn->query("SELECT COUNT(*) AS total FROM appointments");
if ($result) $total_appointments = $result->fetch_assoc()['total'] ?? 0;

$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM appointments WHERE DATE(appointment_date) = ?");
if ($stmt) {
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res) $today_appointments = $res->fetch_assoc()['total'] ?? 0;
    $stmt->close();
}

$result = $conn->query("SELECT COUNT(*) AS total FROM visits");
if ($result) $total_visits = $result->fetch_assoc()['total'] ?? 0;

$result = $conn->query("SELECT COUNT(*) AS total FROM invoices");
if ($result) $total_invoices = $result->fetch_assoc()['total'] ?? 0;

$full_name = $_SESSION['full_name'] ?? 'Admin User';
?>

<!DOCTYPE html>
<html>
<head>
    <title>DentaCare Dashboard</title>

    <style>
        body{
            margin:0;
            font-family:Arial,sans-serif;
            background:#f3f6f8;
            color:#06284a;
        }

        .topbar{
            background:white;
            height:85px;
            padding:0 35px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            border-bottom:1px solid #dce5ec;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:15px;
            font-size:28px;
            font-weight:bold;
        }

        .brand-icon{
            width:55px;
            height:55px;
            background:#2fb8ad;
            border-radius:16px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:28px;
        }

        .right{
            display:flex;
            align-items:center;
            gap:18px;
            font-weight:bold;
        }

        .logout{
            background:#dc3545;
            color:white;
            padding:13px 22px;
            border-radius:12px;
            text-decoration:none;
        }

        .page{
            padding:30px;
        }

        .hero{
            background:linear-gradient(135deg,#2fb8ad,#67d4cc);
            color:white;
            border-radius:26px;
            padding:35px;
            margin-bottom:25px;
            box-shadow:0 10px 25px rgba(47,184,173,.25);
        }

        .hero h1{
            margin:0 0 10px;
            font-size:36px;
        }

        .hero p{
            margin:0;
            font-size:18px;
        }

        .stats{
            display:grid;
            grid-template-columns:repeat(5,1fr);
            gap:16px;
            margin-bottom:25px;
        }

        .stat{
            background:white;
            border-radius:18px;
            padding:20px;
            box-shadow:0 8px 20px rgba(0,0,0,.06);
            border:1px solid #dce5ec;
        }

        .stat-title{
            color:#6f8199;
            font-weight:bold;
            font-size:14px;
        }

        .stat-number{
            font-size:34px;
            font-weight:bold;
            margin-top:12px;
        }

        .quick-title{
            font-size:24px;
            margin:10px 0 18px;
        }

        .cards{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:18px;
        }

        .card{
            background:white;
            border-radius:20px;
            padding:22px;
            min-height:155px;
            box-shadow:0 8px 20px rgba(0,0,0,.06);
            border:1px solid #dce5ec;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
        }

        .card h3{
            margin:0;
            font-size:21px;
        }

        .card p{
            color:#6f8199;
            line-height:1.5;
            font-size:14px;
        }

        .btn{
            width:max-content;
            background:#2fb8ad;
            color:white;
            padding:10px 18px;
            border-radius:11px;
            text-decoration:none;
            font-weight:bold;
        }

        .btn:hover{
            background:#15998f;
        }

        @media(max-width:1100px){
            .stats{grid-template-columns:repeat(2,1fr)}
            .cards{grid-template-columns:repeat(2,1fr)}
        }

        @media(max-width:650px){
            .stats,.cards{grid-template-columns:1fr}
            .topbar{padding:0 18px}
            .right span{display:none}
            .hero h1{font-size:28px}
        }
    </style>
</head>

<body>

<div class="topbar">
    <div class="brand">
        <div class="brand-icon">🩺</div>
        <span>DentaCare</span>
    </div>

    <div class="right">
        <span>Welcome, <?php echo htmlspecialchars($full_name); ?></span>
        <a href="auth/logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="page">

    <div class="hero">
        <h1>Welcome back, <?php echo htmlspecialchars($full_name); ?> 👋</h1>
        <p>Manage your clinic patients, visits, appointments, calendar, and billing from one clean dashboard.</p>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-title">Total Patients</div>
            <div class="stat-number"><?php echo $total_patients; ?></div>
        </div>

        <div class="stat">
            <div class="stat-title">Total Appointments</div>
            <div class="stat-number"><?php echo $total_appointments; ?></div>
        </div>

        <div class="stat">
            <div class="stat-title">Today’s Appointments</div>
            <div class="stat-number"><?php echo $today_appointments; ?></div>
        </div>

        <div class="stat">
            <div class="stat-title">Total Visits</div>
            <div class="stat-number"><?php echo $total_visits; ?></div>
        </div>

        <div class="stat">
            <div class="stat-title">Total Invoices</div>
            <div class="stat-number"><?php echo $total_invoices; ?></div>
        </div>
    </div>

    <h2 class="quick-title">Quick Actions</h2>

    <div class="cards">

        <div class="card">
            <div>
                <h3>Add Patient</h3>
                <p>Create a new patient profile with medical information.</p>
            </div>
            <a href="patients/add_patient.php" class="btn">Open</a>
        </div>

        <div class="card">
            <div>
                <h3>Patients List</h3>
                <p>View, search, edit, and manage all patients.</p>
            </div>
            <a href="patients/list_patients.php" class="btn">Open</a>
        </div>

        <div class="card">
            <div>
                <h3>Add Appointment</h3>
                <p>Schedule a new appointment for a patient.</p>
            </div>
            <a href="appointments/add_appointment.php" class="btn">Open</a>
        </div>

        <div class="card">
            <div>
                <h3>Appointments</h3>
                <p>View and manage all clinic appointments.</p>
            </div>
            <a href="appointments/list_appointments.php" class="btn">Open</a>
        </div>

        <div class="card">
            <div>
                <h3>Calendar</h3>
                <p>Open the appointment calendar view.</p>
            </div>
            <a href="appointments/calendar.php" class="btn">Open</a>
        </div>

        <div class="card">
            <div>
                <h3>Add Invoice</h3>
                <p>Create invoices with treatments and payments.</p>
            </div>
            <a href="billing/add_invoice.php" class="btn">Open</a>
        </div>

        <div class="card">
            <div>
                <h3>Invoices List</h3>
                <p>Track paid and unpaid invoices.</p>
            </div>
            <a href="billing/list_invoices.php" class="btn">Open</a>
        </div>

        <div class="card">
            <div>
                <h3>Visits History</h3>
                <p>Review patient visits and treatment history.</p>
            </div>
            <a href="visits/visit_history.php" class="btn">Open</a>
        </div>

    </div>

</div>

</body>
</html>
