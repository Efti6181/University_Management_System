<?php
session_start();
include("../connection.php");

$student_id = $_GET['vr'] ?? '';

if (empty($student_id)) {
    die("Student ID is required");
}

// Fetch basic student data from student table
$student_query = "SELECT * FROM student WHERE ID = '" . mysqli_real_escape_string($con, $student_id) . "'";
$student_result = mysqli_query($con, $student_query);

if (!$student_result || mysqli_num_rows($student_result) == 0) {
    die("Student not found");
}

$student_data = mysqli_fetch_assoc($student_result);
$name = $student_data['name'];
$sid = $student_data['ID'];
$dept = $student_data['dept'];
$bt = $student_data['batch'];
$p = $student_data['mobileno'];

// Fetch additional student info from st_info table
$info_query = "SELECT * FROM st_info WHERE ID = '" . mysqli_real_escape_string($con, $student_id) . "'";
$info_result = mysqli_query($con, $info_query);
$info = [];

if ($info_result && mysqli_num_rows($info_result) > 0) {
    $info = mysqli_fetch_assoc($info_result);
}

$pageTitle = "Student Full Details - " . $name;
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

        /* .header {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        } */


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
            width: 100%;
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
            font-size: 1.2rem;
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

        .section-title::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #3498db;
            border-radius: 50%;
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
            transform: translateY(-1px);
        }

        .form-group input:disabled,
        .form-group select:disabled,
        .form-group textarea:disabled {
            background: #f5f5f5;
            color: #666;
            cursor: not-allowed;
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

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 10px;
        }

        .blood-group-display {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
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
            
            .header h1 {
                font-size: 2rem;
            }
            
            .profile-photo {
                width: 150px;
                height: 150px;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 200px;
            }
        }

        .success-message {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            display: none;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                    <p>Student Details Portal</p>
                </div>
            </div>
            <nav class="header-nav">
                <a href="admin.php" class="nav-btn">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="view_std.php" class="nav-btn">
                    <span>🔙</span>
                    <span>Back To List</span>
                </a>
            </nav>
        </div>
    </header>
    <!-- <div class="container">
        <div class="header">
            <h1>🎓 Student Details</h1>
            <p>Premier University Chittagong</p>

        </div> -->

        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="profile-photo-container">
                    <div class="profile-photo" id="profilePhoto">📷</div>
                    <div class="photo-upload">
                        <input type="file" id="photoInput" accept="image/*">
                        <!-- <label for="photoInput">Change Photo</label> -->
                    </div>
                </div>

                <div class="student-id-card">
                    <div class="id-number" id="displayStudentId"><?php echo "Name: " . htmlspecialchars($name); ?></div>

                    <!-- <h3>Student ID</h3> -->
                    <div class="id-number" id="displayStudentId"><?php echo "ID: " . htmlspecialchars($sid); ?></div>
                </div>

                <!-- <div class="blood-group-display" id="displayBloodGroup">
                    🩸 B+ Blood Group
                </div> -->

                <!-- <div class="status-badge">
                    ✅ Active Student
                </div> -->

                  <!-- <div class="row">
    <a href="view_std.php" class="back-button">Back to Student List</a>
  </div> -->
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
                                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($info['dob'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($info['gender'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="blood">Blood Group</label>
                                <input type="text" id="blood" name="blood" value="<?php echo htmlspecialchars($info['blood'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nation">Nationality</label>
                                <input type="text" id="nation" name="nation" value="<?php echo htmlspecialchars($info['nation'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="religion">Religion</label>
                                <input type="text" id="religion" name="religion" value="<?php echo htmlspecialchars($info['religion'] ?? ''); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">🎓 Academic Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="semester">Current Semester</label>
                                <input type="text" id="semester" name="semester" value="<?php echo htmlspecialchars($info['semester'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="section">Section</label>
                                <input type="text" id="section" name="section" value="<?php echo htmlspecialchars($info['section'] ?? ''); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">📞 Contact Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="mobileno">Mobile Number *</label>
                                <input type="tel" id="mobileno" name="mobileno" value="<?php echo htmlspecialchars($p); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($info['email'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group full-width">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" readonly><?php echo htmlspecialchars($info['address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">👪 Family Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="fname">Father's Name</label>
                                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($info['fname'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="mname">Mother's Name</label>
                                <input type="text" id="mname" name="mname" value="<?php echo htmlspecialchars($info['mname'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="gnumber">Guardian Contact Number</label>
                                <input type="tel" id="gnumber" name="gnumber" value="<?php echo htmlspecialchars($info['gnumber'] ?? ''); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">🏫 Educational Background</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="sscgrade">SSC Grade/GPA</label>
                                <input type="text" id="sscgrade" name="sscgrade" value="<?php echo htmlspecialchars($info['sscgrade'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="sscyear">SSC Passing Year</label>
                                <input type="number" id="sscyear" name="sscyear" value="<?php echo htmlspecialchars($info['sscyear'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="hscgrade">HSC Grade/GPA</label>
                                <input type="text" id="hscgrade" name="hscgrade" value="<?php echo htmlspecialchars($info['hscgrade'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="hscyear">HSC Passing Year</label>
                                <input type="number" id="hscyear" name="hscyear" value="<?php echo htmlspecialchars($info['hscyear'] ?? ''); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    </script>
</body>
</html>
