<?php
   session_start();
   // if($_SESSION['admin_login_status']!="loged in" and ! isset($_SESSION['user_id']) )
   //   header("Location:../login.php");

   //Sign Out code
   if(isset($_GET['sign']) and $_GET['sign']=="out") {
      $_SESSION['admin_login_status_login_status']="loged out";
      unset($_SESSION['user_id']);
      header("Location:../index.php");    
   }
?>
<!DOCTYPE html>
<html>
<head>
    <style>

        /* Modern Course Creation Form Styles */
@import url("https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=DM+Sans:wght@400;500;600&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "DM Sans", sans-serif;
  background:  linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  color: #1f2937;
  line-height: 1.6;
}

/* .header {
  background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
  color: white;
  padding: 2rem 0;
  text-align: center;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.header h1 {
  font-family: "Space Grotesk", sans-serif;
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
} */

.row {
  max-width: 800px;
  margin: 2rem auto;
  padding: 0 1rem;
}

.container {
  background: white ;
  border-radius: 1rem;
  padding: 1rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  border: 1px solid #e5e7eb;
}

h2 {
  font-family: "Space Grotesk", sans-serif;
  font-size: 2rem;
  font-weight: 600;
  color: #000000ff;
  margin-bottom: 2rem;
  text-align: center;
  position: relative;
}

h2::after {
  content: "";
  position: absolute;
  bottom: -0.5rem;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #6366f1, #8b5cf6);
  border-radius: 2px;
}

.form-row {
  display: flex;
  align-items: center;
  /* margin-bottom: 1.5rem; */
  gap: 1rem;
}

.col-25 {
  flex: 0 0 200px;
}

.col-75 {
  flex: 1;
}

label {
  font-weight: 600;
  color: #374151;
  font-size: 0.95rem;
  display: block;
  /* margin-bottom: 0.5rem; */
}

input[type="text"],
select {
  width: 100%;
  padding: 0.875rem 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 0.5rem;
  font-size: 1rem;
  font-family: "DM Sans", sans-serif;
  background: #ffffff;
  transition: all 0.3s ease;
  color: #1f2937;
}

input[type="text"]:focus,
select:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
  transform: translateY(-1px);
}

select {
  cursor: pointer;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}

input[type="submit"] {
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  color: white;
  padding: 1rem 2.5rem;
  border: none;
  border-radius: 0.5rem;
  font-size: 1.1rem;
  font-weight: 600;
  font-family: "Space Grotesk", sans-serif;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  margin-top: 1rem;
  width: 100%;
}

input[type="submit"]:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  background: linear-gradient(135deg, #5b5ff1 0%, #8553f6 100%);
}

input[type="submit"]:active {
  transform: translateY(0);
}

.success-message {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
  padding: 1rem;
  border-radius: 0.5rem;
  margin-top: 1rem;
  text-align: center;
  font-weight: 600;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
  .header h1 {
    font-size: 2rem;
  }

  .container {
    padding: 2rem 1.5rem;
    margin: 1rem;
    border-radius: 0.75rem;
  }

  .form-row {
    flex-direction: column;
    align-items: stretch;
    gap: 0.5rem;
  }

  .col-25,
  .col-75 {
    flex: none;
    width: 100%;
  }

  h2 {
    font-size: 1.75rem;
  }
}

@media (max-width: 480px) {
  .header {
    padding: 1.5rem 0;
  }

  .header h1 {
    font-size: 1.75rem;
  }

  .container {
    padding: 1.5rem 1rem;
  }

  h2 {
    font-size: 1.5rem;
  }
}

/* Form field animations */
.form-row {
  opacity: 0;
  animation: slideInUp 0.6s ease forwards;
}

.form-row:nth-child(1) {
  animation-delay: 0.1s;
}
.form-row:nth-child(2) {
  animation-delay: 0.2s;
}
.form-row:nth-child(3) {
  animation-delay: 0.3s;
}
.form-row:nth-child(4) {
  animation-delay: 0.4s;
}
.form-row:nth-child(5) {
  animation-delay: 0.5s;
}
.form-row:nth-child(6) {
  animation-delay: 0.6s;
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
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

    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="university-logo">
                <div class="logo-icon">🎓</div>
                <div class="university-info">
                    <h1>Premier University Chittagong</h1>
                    <p>Student Administration Portal</p>
                </div>
            </div>
            <nav class="header-nav">
                <a href="admin.php" class="nav-btn">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
            </nav>
        </div>
    </header>

<div class="row">
<h2 align='center'>Create Course</h2>
  <div class="container">
  <form action="" method="post">
    <div class="row">
      <div class="col-25"><label for="cid">Course ID</label></div>
      <div class="col-75"><input type="text" id="cid" name="cid"  required></div>
    </div>
    <div class="row">
      <div class="col-25"><label for="title">Course Title</label></div>
      <div class="col-75"><input type="text" id="title" name="title" required></div>
    </div>
    <div class="row">
      <div class="col-25"><label for="sem">Semester</label></div>
      <div class="col-75">
        <select id="sem" name="sem" required>
          <option value="1">1st</option>
          <option value="2">2nd</option>
          <option value="3">3rd</option>
          <option value="4">4th</option>
          <option value="5">5th</option>
          <option value="6">6th</option>
          <option value="7">7th</option>
          <option value="8">8th</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-25"><label for="credit">Credit</label></div>
      <div class="col-75">
        <select id="credit" name="credit" required>
          <option value="0.75">0.75</option>
          <option value="1.5">1.5</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-25"><label for="session">Select Session</label></div>
      <div class="col-75">
        <select name="session" required>
        <?php
         include("../connection.php");
         $sql="select name from session where status=1";
         $r=mysqli_query($con,$sql);
         if(mysqli_num_rows($r)>0) {
            while($row=mysqli_fetch_array($r)) {
                $sname=$row['name'];
                echo "<option value='$sname'>$sname</option>";
            }
         } else {
            echo "<option disabled>No Session Found</option>";
         }
        ?>
        </select>
      </div>
    </div>
    <div class="row">
      <input type="submit" value="Save" name="submit">
    </div>
  </form>

  <?php
  if(isset($_POST['submit'])) {
    include("../connection.php");
    $cid=$_POST['cid'];
    $title=$_POST['title'];
    $sem=$_POST['sem'];
    $c=$_POST['credit'];
    $s=$_POST['session'];

    $query="INSERT INTO course(courseid,title,sem,credit,session) 
            VALUES('$cid','$title','$sem',$c,'$s')";
    if(mysqli_query($con,$query)){
        echo "<p class='success-message'>✅ Successfully Added!</p>";
    } else {
        echo "<p class='success-message' style='background:red;'>❌ Error: ".mysqli_error($con)."</p>";
    }
  }
  ?>
</div>
</div>

<!-- Delete Course Section -->
<div class="row">
<h2 align='center'>Delete Course</h2>
  <div class="container">
    <form action="" method="post">
      <div class="row">
        <div class="col-25"><label for="delid">Select Course ID</label></div>
        <div class="col-75">
          <select name="delid" id="delid" required>
            <?php
              include("../connection.php");
              $sql="select courseid, title from course";
              $r=mysqli_query($con,$sql);
              if(mysqli_num_rows($r)>0) {
                  while($row=mysqli_fetch_array($r)) {
                      echo "<option value='".$row['courseid']."'>".$row['courseid']." - ".$row['title']."</option>";
                  }
              } else {
                  echo "<option disabled>No Course Found</option>";
              }
            ?>
          </select>
        </div>
      </div>
      <div class="row">
        <input type="submit" value="Delete" name="delete">
      </div>
    </form>

    <?php
    if(isset($_POST['delete'])) {
      include("../connection.php");
      $delid=$_POST['delid'];
      $query="DELETE FROM course WHERE courseid='$delid'";
      if(mysqli_query($con,$query)) {
          echo "<p class='success-message'>🗑️ Course ID $delid Deleted Successfully!</p>";
      } else {
          echo "<p class='success-message' style='background:red;'>❌ Error: ".mysqli_error($con)."</p>";
      }
    }
    ?>
  </div>
</div>

</body>
</html>
