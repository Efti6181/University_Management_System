<?php
   session_start();
   if($_SESSION['admin_login_status']!="loged in" and ! isset($_SESSION['user_id']) )
    header("Location:../index.php");
   
   if(isset($_GET['sign']) and $_GET['sign']=="out") {
	$_SESSION['admin_login_status']="loged out";
	unset($_SESSION['user_id']);
   header("Location:../index.php");    
   }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Create & Update Session</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0; padding: 0;
      background: #f3f6fa;
    }
    /* .header {
      background: #3498db;
      padding: 20px;
      text-align: center;
      color: white;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    } */
    .container {
      width: 60%;
      margin: 30px auto;
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    .row {
      margin-bottom: 15px;
    }
    .col-25 {
      float: left;
      width: 25%;
      margin-top: 6px;
    }
    .col-75 {
      float: left;
      width: 75%;
      margin-top: 6px;
    }
    label {
      font-weight: bold;
      color: #555;
    }
    input[type=text] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }
    input[type=submit], .btn {
      background: #8d2abeff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 15px;
      transition: 0.3s;
    }
    input[type=submit]:hover, .btn:hover {
      background: #901584ff;
    }
    .clearfix::after {
      content: "";
      display: table;
      clear: both;
    }
    .form-section {
      margin-top: 30px;
      border-top: 2px solid #eee;
      padding-top: 20px;
    }

    		   .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px  0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
		   }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .university-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .university-info h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .university-info p {
            font-size: 0.875rem;
            color: #64748b;
        }

        .header-nav {
            display: flex;
            gap: 1rem;
			margin-left: auto;
        }

        .nav-btn {
			position: right;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        }
		
  </style>
</head>
<body>

<!-- <div class="header">
  <h1>Session Management</h1>
</div> -->


    <header class="header">
        <div class="header-content">
            <div class="university-logo">
                <div class="logo-icon">🎓</div>
                <div class="university-info">
                    <h1>Premier University Chittagong</h1>
                    <p>System Settings Portal</p>
                </div>
            </div>
            <nav class="header-nav">
                <a href="admin.php" class="nav-btn">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
                <!-- <a href="admin.php" class="nav-btn">
                    <span>🔙</span>
                    <span>Back To List</span>
                </a> -->
            </nav>
        </div>
    </header>

<div class="container">
  <!-- Create Session Form -->
  <h2>Create New Session</h2>
  <form action="" method="post">
    <div class="row clearfix">
      <div class="col-25">
        <label for="se">Session Name</label>
      </div>
      <div class="col-75">
        <input type="text" id="se" name="session" placeholder="Enter session name..">
      </div>
    </div>
  
    <div class="row">
      <input type="submit" value="Create Session" name="submit">
    </div>
  </form>
</div>

<div class="container form-section">
  <!-- Update Current Session & Academic Year -->
  <h2>Update Current Session & Academic Year</h2>
  <form action="" method="post">
    <div class="row clearfix">
      <div class="col-25">
        <label for="current">Current Session</label>
      </div>
      <div class="col-75">
        <input type="text" id="current" name="c_ses" placeholder="Enter current session..">
      </div>
    </div>

    <div class="row clearfix">
      <div class="col-25">
        <label for="year">Academic Year</label>
      </div>
      <div class="col-75">
        <input type="text" id="year" name="c_year" placeholder="Enter academic year..">
      </div>
    </div>

    <div class="row">
      <input type="submit" value="Update" name="update">
    </div>
  </form>
</div>

<?php
if(isset($_POST['submit'])) {
    include("../connection.php");
    $id = $_SESSION['user_id'];
    $s  = trim($_POST['session']); // remove extra spaces

    // Check if session already exists
    $check = "SELECT * FROM session WHERE name='$s'";
    $result = mysqli_query($con, $check);

    if(mysqli_num_rows($result) > 0) {
        echo "<script>alert('Session name already exists!');</script>";
    } else {
        $sql = "INSERT INTO session(name,status) VALUES('$s',1)";
        if(mysqli_query($con, $sql)) {
            echo "<script>alert('New session created!');</script>";
        } else {
            echo "Error: ".mysqli_error($con);
        }
    }
}


if(isset($_POST['update'])) {
	include("../connection.php");
    $current = $_POST['c_ses'];
    $year = $_POST['c_year'];

    $sql="UPDATE academic SET c_ses='$current', c_year='$year' WHERE id=1"; 
    // ⚠️ Make sure you have a table named session_settings with id=1 row
    
    if(mysqli_query($con,$sql)) {
		echo "<script>alert('Session & Academic Year updated!');</script>";
	}
	else {
		echo "Error: ".mysqli_error($con);
	}
}
?>

</body>
</html>
