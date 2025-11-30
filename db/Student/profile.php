<?php
   session_start();

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

$sql="select name,dept,mobileno,pic, batch, mobileno from student where ID='$sid'";
$r=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($r);
$name=$row['name'];
$image=$row['pic'];
$dept=$row['dept'];
$mbl=$row['mobileno'];
$bt=$row['batch'];
$p=$row['mobileno'];

$info_sql = "SELECT * FROM st_info WHERE ID='$sid'";
$info_result = mysqli_query($con, $info_sql);
$info = mysqli_fetch_assoc($info_result);

if(isset($_POST['update_profile'])) {
    $success_message = "";
    $error_message = "";
    
    // Handle photo upload
    $profile_image = $image; // Keep existing image by default
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = "../uploads/student_photos/";
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $new_filename = $sid . "_" . time() . "." . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        if(move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
            $profile_image = $upload_path;
            // Update photo in student table
            $photo_sql = "UPDATE student SET pic='$profile_image' WHERE ID='$sid'";
            mysqli_query($con, $photo_sql);
        }
    }
    
    // Update mobile number in student table (if changed)
    $new_mobile = mysqli_real_escape_string($con, $_POST['mobileno']);
    if($new_mobile != $p) {
        $mobile_sql = "UPDATE student SET mobileno='$new_mobile' WHERE ID='$sid'";
        mysqli_query($con, $mobile_sql);
        $p = $new_mobile; // Update local variable
    }
    
    // Prepare data for st_info table
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $blood = mysqli_real_escape_string($con, $_POST['blood']);
    $nation = mysqli_real_escape_string($con, $_POST['nation']);
    $religion = mysqli_real_escape_string($con, $_POST['religion']);
    $semester = mysqli_real_escape_string($con, $_POST['semester']);
    $section = mysqli_real_escape_string($con, $_POST['section']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $mname = mysqli_real_escape_string($con, $_POST['mname']);
    $gnumber = mysqli_real_escape_string($con, $_POST['gnumber']);
    $sscgrade = mysqli_real_escape_string($con, $_POST['sscgrade']);
    $sscyear = mysqli_real_escape_string($con, $_POST['sscyear']);
    $hscgrade = mysqli_real_escape_string($con, $_POST['hscgrade']);
    $hscyear = mysqli_real_escape_string($con, $_POST['hscyear']);
    
    // Check if record exists in st_info table
    $check_sql = "SELECT ID FROM st_info WHERE ID='$sid'";
    $check_result = mysqli_query($con, $check_sql);
    
    if(mysqli_num_rows($check_result) > 0) {
        // Update existing record
        $update_sql = "UPDATE st_info SET 
                       dob='$dob', 
                       gender='$gender', 
                       blood='$blood', 
                       nation='$nation', 
                       religion='$religion', 
                       semester='$semester', 
                       section='$section', 
                       email='$email', 
                       address='$address', 
                       fname='$fname', 
                       mname='$mname', 
                       gnumber='$gnumber', 
                       sscgrade='$sscgrade', 
                       sscyear='$sscyear', 
                       hscgrade='$hscgrade', 
                       hscyear='$hscyear'
                       WHERE ID='$sid'";
        
        if(mysqli_query($con, $update_sql)) {
            $success_message = "Profile updated successfully!";
        } else {
            $error_message = "Error updating profile: " . mysqli_error($con);
        }
    } else {
        // Insert new record
        $insert_sql = "INSERT INTO st_info (
                       ID, dob, gender, blood, nation, religion, 
                       semester, section, email, address, fname, mname, 
                       gnumber, sscgrade, sscyear, hscgrade, hscyear
                       ) VALUES (
                       '$sid', '$dob', '$gender', '$blood', '$nation', '$religion', 
                       '$semester', '$section', '$email', '$address', '$fname', '$mname', 
                       '$gnumber', '$sscgrade', '$sscyear', '$hscgrade', '$hscyear'
                       )";
        
        if(mysqli_query($con, $insert_sql)) {
            $success_message = "Profile created successfully!";
        } else {
            $error_message = "Error creating profile: " . mysqli_error($con);
        }
    }
    
    // Refresh info data after update/insert
    $info_result = mysqli_query($con, $info_sql);
    $info = mysqli_fetch_assoc($info_result);
}



// Set profile image for display
$profile_image = $image;
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - University Management System</title>
    <style>
                * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            /* padding: 20px; */
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

 
        .profile-content {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 0;
            min-height: 600px;
        }

        .profile-sidebar {
            background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 40px 30px;
            border-right: 1px solid #e0e0e0;
        }

        .profile-photo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-photo {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 6px solid #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            object-fit: cover;
            background: linear-gradient(135deg, #e3e3e3 0%, #f0f0f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #999;
            margin: 0 auto;
            overflow: hidden;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-upload {
            margin-top: 15px;
        }

        .photo-upload input[type="file"] {
            display: none;
        }

        .photo-upload label {
            background: #3498db;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .photo-upload label:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .student-id-card {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 20px;
        }

        .student-id-card h3 {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .student-id-card .id-number {
            font-size: 1.4rem;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .profile-main {
            padding: 40px;
            background: white;
        }

        .profile-form {
            display: grid;
            gap: 30px;
        }

        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            border-left: 4px solid #3498db;
        }

        .section-title {
            font-size: 1.4rem;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin: 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
                        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            opacity: 0.8;
        }

        .modal-body {
            padding: 30px;
        }

        @media (max-width: 768px) {
            .profile-content {
                grid-template-columns: 1fr;
            }
            
            .profile-sidebar {
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
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
                <!-- <a href="#" class="nav-btn">
                    <span>🏠</span>
                    <span>Home</span>
                </a> -->
                <a href="home.php" class="nav-btn">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
                <!-- <a href="#" class="nav-btn">
                    <span>❓</span>
                    <span>Help</span>
                </a> -->
            </nav>
        </div>
    </header>

        <!-- Display success/error messages -->
        <?php if(isset($success_message) && !empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error_message) && !empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-content">
            <div class="profile-sidebar">
                <form method="post" enctype="multipart/form-data" style="display: none;" id="photoForm">
                    <div class="profile-photo-container">
                        <!-- <div class="profile-photo">
                <?php if(!empty($rows['pic'])): ?>
                    <img src="Simage/<?php echo htmlspecialchars($rows['pic']);?>" width="50" height="50" alt="<?php echo htmlspecialchars($rows['pic']);?>" />
                <?php else: ?>
                    <span>No Photo</span>
                <?php endif; ?>
                        </div> -->
                        <!-- <div class="photo-upload">
                            <input type="file" id="photoInput" name="photo" accept="image/*">
                            <label for="photoInput">Change Photo</label>
                        </div> -->
                    </div>
                </form>
                <div class="profile-photo-container">
                    <!-- <div class="profile-photo" id="profilePhotoDisplay">
                        <?php if($profile_image): ?>
                            <img src="<?php echo $profile_image; ?>" alt="Profile Photo">
                        <?php else: ?>
                            📷
                        <?php endif; ?>
                    </div> -->
                    <!-- <div class="photo-upload">
                        <label for="photoInput">Change Photo</label>
                    </div> -->
                          <?php       
echo "<div style='text-align: middle; padding: 20px 0;'>
        <img src='../Admin/Simage/$image' style='width: 220px; height: 220px; border-radius: 50%; object-fit: cover; border: 3px solid #ddd;'>
      </div>";    
?>
                </div>
                                <div class="student-id-card">
                    <!-- <h3>Student ID</h3> -->
                    <div class="id-number" ><?php echo htmlspecialchars($name ?? ''); ?></div>
                    <!-- <h3>Student ID</h3> -->
                    <!-- <div class="id-number"><?php echo htmlspecialchars($sid ?? ''); ?></div> -->
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <button type="button" class="btn btn-danger" ><a href="change.php">
                        🔐 Change Password
                    </a></button>
                </div>
            </div>
            
            <div class="profile-main">
                <form class="profile-form" method="post" enctype="multipart/form-data">
                    <div class="form-section">
                        <h3 class="section-title">👤 Basic Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="id">Student ID *</label>
                                <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($sid); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="dept">Department *</label>
                                <input type="text" id="dept" name="dept" value="<?php echo htmlspecialchars($dept); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="batch">Batch *</label>
                                <input type="text" id="batch" name="batch" value="<?php echo htmlspecialchars($bt); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">📋 Personal Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($info['dob'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php echo (isset($info['gender']) && $info['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo (isset($info['gender']) && $info['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo (isset($info['gender']) && $info['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="blood">Blood Group</label>
                                <select id="blood" name="blood">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" <?php echo (isset($info['blood']) && $info['blood'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                                    <option value="A-" <?php echo (isset($info['blood']) && $info['blood'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                                    <option value="B+" <?php echo (isset($info['blood']) && $info['blood'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                                    <option value="B-" <?php echo (isset($info['blood']) && $info['blood'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                                    <option value="AB+" <?php echo (isset($info['blood']) && $info['blood'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                    <option value="AB-" <?php echo (isset($info['blood']) && $info['blood'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                    <option value="O+" <?php echo (isset($info['blood']) && $info['blood'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                                    <option value="O-" <?php echo (isset($info['blood']) && $info['blood'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nation">Nationality</label>
                                <input type="text" id="nation" name="nation" value="<?php echo htmlspecialchars($info['nation'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="religion">Religion</label>
                                <input type="text" id="religion" name="religion" value="<?php echo htmlspecialchars($info['religion'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">🎓 Academic Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="semester">Current Semester</label>
                                <input type="text" id="semester" name="semester" value="<?php echo htmlspecialchars($info['semester'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="section">Section</label>
                                <input type="text" id="section" name="section" value="<?php echo htmlspecialchars($info['section'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">📞 Contact Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="mobileno">Mobile Number *</label>
                                <input type="tel" id="mobileno" name="mobileno" value="<?php echo htmlspecialchars($p); ?>" >
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($info['email'] ?? ''); ?>">
                            </div>
                            <div class="form-group full-width">
                                <label for="address">Address</label>
                                <textarea id="address" name="address"><?php echo htmlspecialchars($info['address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">👪 Family Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="fname">Father's Name</label>
                                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($info['fname'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="mname">Mother's Name</label>
                                <input type="text" id="mname" name="mname" value="<?php echo htmlspecialchars($info['mname'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="gnumber">Guardian Contact Number</label>
                                <input type="tel" id="gnumber" name="gnumber" value="<?php echo htmlspecialchars($info['gnumber'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">🏫 Educational Background</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="sscgrade">SSC Grade/GPA</label>
                                <input type="text" id="sscgrade" name="sscgrade" value="<?php echo htmlspecialchars($info['sscgrade'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="sscyear">SSC Passing Year</label>
                                <input type="number" id="sscyear" name="sscyear" value="<?php echo htmlspecialchars($info['sscyear'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="hscgrade">HSC Grade/GPA</label>
                                <input type="text" id="hscgrade" name="hscgrade" value="<?php echo htmlspecialchars($info['hscgrade'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="hscyear">HSC Passing Year</label>
                                <input type="number" id="hscyear" name="hscyear" value="<?php echo htmlspecialchars($info['hscyear'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" name="update_profile" class="btn btn-primary">💾 Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
    </script>
</body>
</html>
