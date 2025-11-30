<?php
   session_start();
  //  if($_SESSION['student_login_status']!="loged in" and ! isset($_SESSION['user_id']) )
  //   header("Location:../login.php");
   //Sign Out code
   if(isset($_GET['sign']) and $_GET['sign']=="out") {
	$_SESSION['student_login_status']="loged out";
	unset($_SESSION['user_id']);
   header("Location:../index.php");    
   }
?> 

	  <?php
 include("../connection.php");
 $sid=$_SESSION['user_id'];
 $sql="select name,dept,mobileno,pic from student where ID='$sid'";
 $r=mysqli_query($con,$sql);
 $row=mysqli_fetch_assoc($r);
 $name=$row['name'];
 $image=$row['pic'];
 $dept=$row['dept'];
 $mbl=$row['mobileno'];
?>  
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>University Management System - Student</title>
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
      background: #004080;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 20px;
      height: 1000px;
    }

    .sidebar img {
      width: 90px;
      margin-bottom: 10px;
    }

    .sidebar h2 {
      font-size: 18px;
      margin-bottom: 25px;
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
      background: #0066cc;
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
    h1, h2, h3 {
      margin: 2px 0;
    }
    h1
    {
      padding-top: 1px;
    }

    .content-box {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      min-height: 400px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table, th, td {
      border: 1px solid #ccc;
    }

    th, td {
      padding: 10px;
      text-align: center;
    }

    form input, form textarea, form button {
      width: 100%;
      margin: 8px 0;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #aaa;
    }

    form button {
      background: #004080;
      color: white;
      border: none;
      cursor: pointer;
    }

    form button:hover {
      background: #003366;
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
    <h2>Student Panel</h2>
    <ul class="nav-links">
      <li><a href="profile.php" class="">👤 My Profile</a></li>
      <li><a href="enroll.php" class="">🖥️ Enrollment</a></li>
      <li><a href="mcour.php">📚 My Courses</a></li>
      <li><a href="routin.php">📅 Class Routine</a></li>
      <li><a href="st_res.php">📝 My Result</a></li>
      <li><a href="complain.php">✉️ Complaint</a></li>
      <li><a href="?sign=out">🔐  Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header">
      <?php       
echo "<div style='text-align: left; padding: 20px 0;'>
        <img src='../Admin/Simage/$image' style='width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #ddd;'>
      </div>";    
?>
      <h1>Welcome, <?php echo "$name."; ?></h1><br>
      <h2>Student of Premier University Chittagong</h2>
      <h2>Student ID: <?php echo "$sid."; ?></h2>
      <h2>Department of <?php echo "$dept."; ?></h2>

    </div>
    <div class="content-box" id="content">
      <h2>Dashboard</h2>
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
      <p>Select an option from the left menu to view your details.</p>
    
    </div>
  </div>

  <script>
    function loadContent(page) {
      const content = document.getElementById("content");
      let html = "";

      switch(page) {
        case "profile":
          html = `
            <h2>My Profile</h2>
            <p><b>Name:</b> John Doe</p>
            <p><b>ID:</b> 2025001</p>
            <p><b>Department:</b> Computer Science</p>
            <p><b>Email:</b> john@student.university.edu</p>
          `;
          break;
        case "courses":
          html = `
            <h2>My Courses</h2>
            <ul>
              <li>Programming in C</li>
              <li>Database Systems</li>
              <li>Data Structures</li>
              <li>Operating Systems</li>
            </ul>
          `;
          break;
        case "routine":
          html = `
            <h2>Class Routine</h2>
            <table>
              <tr><th>Day</th><th>Course</th><th>Time</th></tr>
              <tr><td>Sunday</td><td>Programming in C</td><td>9:00 - 10:30</td></tr>
              <tr><td>Monday</td><td>Database Systems</td><td>11:00 - 12:30</td></tr>
              <tr><td>Wednesday</td><td>Data Structures</td><td>9:00 - 10:30</td></tr>
              <tr><td>Thursday</td><td>Operating Systems</td><td>11:00 - 12:30</td></tr>
            </table>
          `;
          break;
        case "grades":
          html = `
            <h2>Grades</h2>
            <table>
              <tr><th>Course</th><th>Grade</th></tr>
              <tr><td>Programming in C</td><td>A</td></tr>
              <tr><td>Database Systems</td><td>B+</td></tr>
              <tr><td>Data Structures</td><td>A-</td></tr>
              <tr><td>Operating Systems</td><td>B</td></tr>
            </table>
          `;
          break;
        case "complaint":
          html = `
            <h2>Submit Complaint</h2>
            <form>
              <input type="text" placeholder="Subject" required>
              <textarea placeholder="Write your complaint here..." rows="5" required></textarea>
              <button type="submit">Submit</button>
            </form>
          `;
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
