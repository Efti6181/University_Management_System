
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <title>Sign In</title>
  </head>
  <body>
    <div class="container" id="container">
      <div class="login-container">
        <img src="logo.png" alt="University Logo">
        <form id="loginForm" action="index.php" method="POST">
          <h2>Premier University Chittagong</h2>
          <ul class="example-2"></ul>
          <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
            <option value="admin">Admin</option>
          </select>
          <input type="text" id="uid" name="id" placeholder="Enter ID" required />
          <input type="password" id="pass" name="password" placeholder="Enter Password" required />
          <button type="submit" name="signIn">Sign In</button>
        </form>
      </div>
    </div>
    <script>
      // (function(){
      //   var form = document.getElementById('loginForm');
      //   if (!form) return;
      //   form.addEventListener('submit', function(e){
      //     var role = (document.querySelector('select[name="role"]') || {}).value || '';
      //     var id = (document.getElementById('uid') || {}).value || '';
      //     var password = (document.getElementById('pass') || {}).value || '';
      //     if (role === 'admin' && id === 'admin12345' && password === '1234567') {
      //       e.preventDefault();
      //       window.location.href = 'Admin/admin.php';
      //     }
        
      //   });
      // })();
    </script>
    <script src="script.js"></script>
  </body>
</html>

<?php
include("connection.php");
if(isset($_POST['signIn']))
{
    $id=$_POST['id'];
    $pass=$_POST['password'];
    $role=$_POST['role'];

    // Only allow PHP login for student and faculty
    if ($role == 'faculty') {
        $sql="select tid,password from teacher where tid='$id' and password='$pass'";
        $tr=mysqli_query($con,$sql);
        if(mysqli_num_rows($tr)>0)
        {
            $_SESSION['user_id']=$id;
            $_SESSION['teacher_login_status']="loged in";
            header("Location:Teacher/home.php");
            exit();
        }
    } else if ($role == 'student') {
        $sql1="select ID,password from student where ID='$id' and password='$pass'";
        $sr=mysqli_query($con,$sql1);
        if(mysqli_num_rows($sr)>0)
        {
            $_SESSION['user_id']=$id;
            $_SESSION['student_login_status']="loged in";
            header("Location:Student/home.php");
            exit();
        }
    }
    else if ($role == 'admin') {
        $sql1="select ID,password from admin where ID='$id' and password='$pass'";
        $sr=mysqli_query($con,$sql1);
        if(mysqli_num_rows($sr)>0)
        {
            $_SESSION['user_id']=$id;
            $_SESSION['admin_login_status']="loged in";
            header("Location:Admin/admin.php");
            exit();
        }
    }
    echo "<p style='color: red;'>Incorrect UserId or Password</p>";
}
?>