<?php
   session_start();

   if(isset($_GET['sign']) and $_GET['sign']=="out") {
	$_SESSION['student_login_status']="loged out";
	unset($_SESSION['user_id']);
   header("Location:../index.php");    
   }
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="signup.css">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<style>
/* Professional Password Change Form Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.row {
  background: white;
  border-radius: 12px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  width: 100%;
  max-width: 500px;
}

h2 {
  background: #2c3e50;
  color: white;
  padding: 30px 20px;
  margin: 0;
  font-size: 24px;
  font-weight: 600;
  text-align: center;
  letter-spacing: 0.5px;
}

.container {
  padding: 40px 30px;
}

.row .row {
  background: none;
  box-shadow: none;
  border-radius: 0;
  margin-bottom: 25px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.col-25 {
  width: 100%;
}

.col-75 {
  width: 100%;
}

label {
  font-weight: 600;
  color: #2c3e50;
  font-size: 14px;
  margin-bottom: 5px;
  display: block;
}

input[type="password"] {
  width: 100%;
  padding: 15px 18px;
  border: 2px solid #e1e8ed;
  border-radius: 8px;
  font-size: 16px;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

input[type="password"]:focus {
  outline: none;
  border-color: #667eea;
  background: white;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

input[type="submit"] {
  width: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 16px 24px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 20px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

input[type="submit"]:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

input[type="submit"]:active {
  transform: translateY(0);
}

.back-button {
  display: block;
  width: 100%;
  background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
  color: white;
  padding: 16px 24px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 15px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  text-decoration: none;
  text-align: center;
  box-sizing: border-box;
}

.back-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(52, 73, 94, 0.3);
  text-decoration: none;
  color: white;
}

.back-button:active {
  transform: translateY(0);
}


.message {
  padding: 15px;
  border-radius: 8px;
  margin-top: 20px;
  font-weight: 500;
  text-align: center;
}

.success {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.error {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}


@media (max-width: 768px) {
  body {
    padding: 10px;
  }

  .container {
    padding: 30px 20px;
  }

  h2 {
    padding: 25px 15px;
    font-size: 20px;
  }
}

@media (max-width: 480px) {
  .row {
    margin: 10px;
  }

  .container {
    padding: 25px 15px;
  }

  input[type="password"],
  input[type="submit"],
  .back-button {
    padding: 12px 15px;
    font-size: 14px;
  }
}

</style>
<body>
  
                  


<div class="row">
<h2 align='center'>Change Your Password</h2>
  <div class="container">
  <form action="change.php" method="post">
    <div class="row">
    <div class="col-25">
      <label for="pass">Old Password</label>
    </div>
    <div class="col-75">
      <input type="password" id="pass" name="opass" placeholder="Your old password..">
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label for="pass">New Password</label>
    </div>
    <div class="col-75">
      <input type="password" id="pass" name="npass" placeholder="Your new password..">
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label for="pass">Confirm Password</label>
    </div>
    <div class="col-75">
      <input type="password" id="pass" name="cpass" placeholder="Retype Your password..">
    </div>
  </div>
  <div class="row">
    <input type="submit" value="Change Password" name="submit">
  <div class="row">
    <a href="profile.php" class="back-button">Back to Profile</a>
  </div>


  </div>
  </form>
</div>
<?php
if(isset($_POST['submit']))
{
	include("../connection.php");
    $id=$_SESSION['user_id'];
    $opass=$_POST['opass'];
    $npass=$_POST['npass'];
	$cpass=$_POST['cpass'];
	if($npass==$cpass)
	{
	$sql="select password from student where password='$opass' and ID='$id'";
    $r=mysqli_query($con,$sql);
    if(mysqli_num_rows($r)>0)
    {
       $sql1="update student set password='$npass' where ID='$id'"; 
       if(mysqli_query($con,$sql1))
       {
         echo "Password Changed Successfully!";
       }  
    }
	else
	{
		echo "Old password does not match";
	}
	}
    else
    {
        echo "New password does not match with Confirm password";
    }
}
?>

</body>
</html>
