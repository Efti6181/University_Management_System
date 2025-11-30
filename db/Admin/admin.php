<?php
   session_start();
   
   if(isset($_GET['sign']) and $_GET['sign']=="out") {
	$_SESSION['teacher_login_status']="loged out";
	unset($_SESSION['user_id']);
   header("Location:../index.php");    
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>University Management System - Admin</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      display: flex;
      height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: #003366;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 20px;
    }

    .sidebar img {
      width: 100px;
      margin-bottom: 15px;
    }

    .sidebar h2 {
      font-size: 18px;
      margin-bottom: 30px;
    }

    .nav-links {
      list-style: none;
      padding: 0;
      width: 100%;
    }

    .nav-links li {
      width: 100%;
    }

    .nav-links a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      transition: 0.3s;
    }

    .nav-links a:hover,
    .nav-links a.active {
      background: #0055a5;
    }

    /* Main Content */
    .main-content {
      flex: 1;
      padding: 20px;
    }

    .header {
      background: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    .content-box {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

.label-title {
  font-size: 18px;
  font-weight: 600;
  color: #2c3e50;
  margin: 8px 0;
}

  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <img src="logo.png" alt="University Logo">
    <h2>Admin Panel</h2>
    <ul class="nav-links">
      <li><a href="adminpp.php" class="active">👤 Admin Profile</a></li>
      <li><a href="stdsign.php">🎓 Admit Student</a></li>
      <li><a href="techsign.php">👨‍🏫 Add Faculty</a></li>
      <li><a href="view_std.php">👨‍🎓 Student List</a></li>
      <li><a href="view_fac.php">👩‍🏫 Faculty List</a></li>
      <li><a href="result_std.php">📝 Student Result</a></li>
      <li><a href="rout.php">📆 Upload Routine</a></li>
      <li><a href="course.php">📚 Course Management</a></li>
      <li><a href="view_com.php">📝 View Complaints</a></li>
      <li><a href="set.php">⚙️ System Settings</a></li>
      <li><a href="?sign=out" class="logout-btn">🔐 Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header">
      <h1>Welcome, Admin</h1>
    </div>
    <div class="content-box" id="content">
      <h2>Dashboard</h2>
      <!-- <div class="label-title">Current Term: </div>
<div class="label-title">Academic Cycle: </div> -->
<?php
include("../connection.php"); // your DB connection file

// Query the academic table
$sql = "SELECT c_ses, c_year FROM academic ORDER BY id DESC LIMIT 1"; 
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $currentSession = $row['c_ses'];
    $currentYear = $row['c_year'];

    echo "<div class='label-title'>Current Term: " . htmlspecialchars($currentSession) . "</div>";
    echo "<div class='label-title'>Academic Cycle: " . htmlspecialchars($currentYear) . "</div>";
} 
?>


      <p>Select an option from the left menu to manage the university system.</p>
    </div>
  </div>

  <script>
    function loadContent(page) {
      const content = document.getElementById("content");
      let html = "";

      switch(page) {
        case "profile":
          html = "<h2>Admin Profile</h2><p>Manage your profile information here.</p>";
          break;
        case "student":
          html = "<h2>Admit Student</h2><p>Form to admit a new student will appear here.</p>";
          break;
        case "faculty":
          html = "<h2>Add Faculty</h2><p>Form to add new faculty details will appear here.</p>";
          break;
        case "course":
          html = "<h2>Add Course</h2><p>Form to create a new course will appear here.</p>";
          break;
        case "result":
          html = "<h2>Publish Student Result</h2><p>Upload or publish student results here.</p>";
          break;
        case "settings":
          html = "<h2>System Settings</h2><p>Adjust system configuration options here.</p>";
          break;
      }

      content.innerHTML = html;

      // Update active state
      document.querySelectorAll(".nav-links a").forEach(a => a.classList.remove("active"));
      event.target.classList.add("active");
    }
  </script>
</body>
</html>
