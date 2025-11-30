<?php
session_start();
include('../connection.php');

// Check if admin is logged in
// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: admin-login.php");
//     exit();
// }

$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['admin_name'] ?? '';

$success_message = '';
$error_message = '';

// Fetch admin info from admin_info table
$info_sql = "SELECT * FROM admin_info WHERE admin_id='$admin_id'";
$info_result = mysqli_query($con, $info_sql);
$info = mysqli_fetch_assoc($info_result);

// Handle form submission
if(isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $join_date = mysqli_real_escape_string($con, $_POST['join_date']);
    $mobile_no = mysqli_real_escape_string($con, $_POST['mobile_no']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // Check if record exists
    $check_sql = "SELECT admin_id FROM admin_info WHERE admin_id='$admin_id'";
    $check_result = mysqli_query($con, $check_sql);
    
    if(mysqli_num_rows($check_result) > 0) {
        // Update existing record
        $update_sql = "UPDATE admin_info SET 
                       name='$name', 
                       join_date='$join_date', 
                       mobile_no='$mobile_no', 
                       email='$email',
                       updated_at=NOW()
                       WHERE admin_id='$admin_id'";
        
        if(mysqli_query($con, $update_sql)) {
            $success_message = "Profile updated successfully!";
            // Refresh data
            $info_result = mysqli_query($con, $info_sql);
            $info = mysqli_fetch_assoc($info_result);
        } else {
            $error_message = "Error updating profile: " . mysqli_error($con);
        }
    } else {
        // Insert new record
        $insert_sql = "INSERT INTO admin_info (admin_id, name, join_date, mobile_no, email, created_at, updated_at) 
                       VALUES ('$admin_id', '$name', '$join_date', '$mobile_no', '$email', NOW(), NOW())";
        
        if(mysqli_query($con, $insert_sql)) {
            $success_message = "Profile created successfully!";
            // Refresh data
            $info_result = mysqli_query($con, $info_sql);
            $info = mysqli_fetch_assoc($info_result);
        } else {
            $error_message = "Error creating profile: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - University Management System</title>
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
            /* border-radius: 20px; */
            /* box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); */
            overflow: hidden;
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
                    <p>Admin Profile Portal</p>
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

    <!-- <div class="container"> -->
        <div class="profile-main">
            <!-- Display success/error messages -->
            <?php if(!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form class="profile-form" method="post" enctype="multipart/form-data">
                <div class="form-section">
                    <h3 class="section-title">👤 Basic Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
      
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($info['name'] ?? ''); ?>" placeholder="Enter your full name">
                        </div>
                         <div class="form-group">
                            <label for="admin_id">Admin ID *</label>
                            <input type="text" id="admin_id" name="admin_id" value="<?php echo htmlspecialchars($admin_id); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" id="role" name="role" value="System Administrator" readonly>
                        </div>
                                                <div class="form-group">
                            <label for="join_date">Join Date</label>
                            <input type="date" id="join_date" name="join_date" value="<?php echo htmlspecialchars($info['join_date'] ?? ''); ?>">
                        </div>
                    </div>
                </div>


                <div class="form-section">
                    <h3 class="section-title">📞 Contact Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="mobile_no">Mobile Number</label>
                            <input type="tel" id="mobile_no" name="mobile_no" value="<?php echo htmlspecialchars($info['mobile_no'] ?? ''); ?>" placeholder="Enter your mobile number">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($info['email'] ?? ''); ?>" placeholder="Enter your email address">
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" name="update_profile" class="btn btn-primary">💾 Update Profile</button>
                    <!-- <a href="admin-dashboard.php" class="btn btn-secondary">← Back to Dashboard</a> -->
                </div>
            </form>
        </div>
    </div>
</body>
</html>
