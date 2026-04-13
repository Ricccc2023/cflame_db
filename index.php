<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>C'Flame Fire Protection Product Trading</title>

    <style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* BODY */
body {
  background-color: #FFF3E0;
  font-family: Arial, sans-serif;
}

/* BACKGROUND LOGO */
body::before {
  content: "";
  position: fixed;
  inset: 0;
  background-image: url('logo.png');
  background-repeat: repeat;
  background-size: 200px;
  opacity: 0.05;
  z-index: -1;
  pointer-events: none;
}

/* TOPBAR */
.topbar {
  background: #991e1e;
  color: white;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;

  /* MAKE SURE NO SPACE */
  width: 100%;
}

/* TEXT STYLE */
.topbar small {
  font-size: 13px;
  font-weight: normal;
  display: block;
}

        .admin-btn {
            background: white;
            color: #1f4e46;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
        }

        .center-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 60px;
        }

        .booking-card {
            background: white;
            width: 500px;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            text-align: center;
        }

        .section-box {
            display: inline-block;
            background: #f1f1f1;
            padding: 8px 14px;
            border-radius: 6px;
            margin: 5px;
            font-size: 14px;
        }

        .action-btn {
            display: inline-block;
            background: #bb4747;
            color: white;
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .action-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <div>
        C'Flame Fire Protection Product Trading
        <small>
            Welcome, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Guest') ?>
        </small>
    </div>

        <a href="login.php" class="admin-btn">
    Admin Login
</a>
</div>

<!-- MAIN CONTENT -->
<div class="center-wrapper">
    <div class="booking-card">

        <div style="margin-bottom:20px;">
            <img src="image.png" alt="Clinic Logo" style="width:90px; border-radius:15px;">
        </div>

        <h1 style="font-size:34px; margin-bottom:10px; color:#1f4e46;">
            Patient Portal
        </h1>

        <p style="font-size:15px; margin-bottom:20px; color:#555;">
            Your trusted partner for your safety
        </p>

        <div style="margin-bottom:25px;">
            <span class="section-box">MPJR BLDG General Malvar Ave, Poblacion 4</span>
        </div>

        <div class="section-box" style="text-align:left; margin-bottom:25px; display:block;">
            <b>HOW TO USE THIS PORTAL (GUIDE)</b>
            <ol style="margin-top:10px; padding-left:18px; font-size:14px;">
                <li>Book Appointment – Fill up the form and submit.</li>
                <li>Wait for Admin Approval to confirm your order</li>
            </ol>
        </div>

        <a href="booking.php" class="action-btn">
            📅 Book Delivery
        </a>

    </div>
</div>

</body>
</html>