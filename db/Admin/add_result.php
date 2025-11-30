<?php
// filepath: c:\xampp\htdocs\db\Admin\add_result.php
session_start();
include("../connection.php");

$pageTitle = "Student Result";

// Get student info from request
if (isset($_REQUEST['ar']) && isset($_REQUEST['vn'])) {
    $stid = $_REQUEST['ar']; // Student ID
    $name = $_REQUEST['vn']; // Student Name
} else {
    echo "<p style='color:red;text-align:center'>No student selected.</p>";
    exit();
}

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $semester = $_POST['semester'];
    $marks = $_POST['marks'];

    // Check if a result already exists for this student, subject, and semester
    $check_sql = "SELECT * FROM result WHERE ID='$stid' AND sub='$subject' AND semester='$semester'";
    $check_result = mysqli_query($con, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $row = mysqli_fetch_assoc($check_result);
        if ($row['marks'] == $marks) {
            $message = "<p class='msg error'>Same value already exists!</p>";
        } else {
            // Update marks
            $update_sql = "UPDATE result SET marks='$marks' WHERE ID='$stid' AND sub='$subject' AND semester='$semester'";
            if (mysqli_query($con, $update_sql)) {
                $message = "<p class='msg success'>Marks updated!</p>";
            } else {
                $message = "<p class='msg error'>Failed to update marks: " . mysqli_error($con) . "</p>";
            }
        }
    } else {
        // Insert new result
        $insert_sql = "INSERT INTO result (ID, sub, semester, marks) VALUES ('$stid', '$subject', '$semester', '$marks')";
        if (mysqli_query($con, $insert_sql)) {
            $message = "<p class='msg success'>Marks successfully inserted!</p>";
        } else {
            $message = "<p class='msg error'>Failed to insert data: " . mysqli_error($con) . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }
        .header {
            width: 100vw;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 2rem;
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
        .all_student {
            width: 100%;
            max-width: 480px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0px 6px 18px rgba(0,0,0,0.2);
            overflow: hidden;
            animation: fadeIn 0.5s ease-in-out;
            margin: 120px auto 0 auto; /* space below header */
        }
        .form-header {
            background: #6a1b9a;
            color: #fff;
            padding: 18px 24px 10px 24px;
            text-align: center;
        }
        .form-header h3 {
            margin-bottom: 6px;
            font-size: 22px;
            font-weight: 500;
        }
        .form-header p {
            font-size: 15px;
            font-weight: 400;
        }
        .student-info {
            background: #f3e6fa;
            color: #6a1b9a;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        .form-body {
            padding: 24px;
        }
        .form-section-title {
            font-size: 17px;
            font-weight: 500;
            color: #764ba2;
            margin-bottom: 18px;
            text-align: left;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            margin-bottom: 7px;
            font-size: 15px;
            color: #333;
        }
        .form-select, .form-input {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            font-size: 15px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-select:focus, .form-input:focus {
            border-color: #764ba2;
            box-shadow: 0 0 5px rgba(118, 75, 162, 0.2);
        }
        .submit-section {
            text-align: center;
            margin-top: 18px;
        }
        .submit-btn {
            padding: 10px 22px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            background: #4CAF50;
            color: white;
            margin-right: 10px;
            transition: background 0.3s, transform 0.2s;
        }
        .submit-btn:hover {
            background: #43a047;
            transform: scale(1.05);
        }
        .reset-btn {
            background: #f44336;
            color: white;
        }
        .reset-btn:hover {
            background: #d32f2f;
        }
        .back {
            text-align: center;
            padding: 20px;
        }
        .editbtn {
            background: #2196F3;
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s;
        }
        .editbtn:hover {
            background: #1976d2;
            transform: scale(1.05);
        }
        .msg {
            text-align: center;
            padding: 10px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .msg.success {
            color: #43a047;
        }
        .msg.error {
            color: #d32f2f;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <!-- Header (full width, fixed at top) -->
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
                                
                <a href="result_std.php" class="nav-btn">
                    <span>🔙</span>
                    <span>Back To List</span>
                </a>
            </nav>
        </div>
    </header>
    <!-- Form container below header -->
    <div class="all_student">
        <div class="form-header">
            <h3>Add/Update Student Result</h3>
            <p>Enter subject, semester, and marks for the selected student</p>
        </div>
        <div class="student-info">
            <?php echo "Name: " . htmlspecialchars($name) . "<br>Student ID: " . htmlspecialchars($stid); ?>
        </div>
        <?php echo $message; ?>
        <form class="form-body" action="" method="post">
            <div class="form-section-title">Result Information</div>
            <div class="form-group">
                <label class="form-label" for="subject">
                    Subject <span style="color:#d32f2f">*</span>
                </label>
                <select id="subject" name="subject" class="form-select" required>
                    <option value="DBMS">Database management</option>
                    <option value="DBMS Lab">DBMS Lab</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Programming">Programming</option>
                    <option value="Programming Lab">Programming Lab</option>
                    <option value="English">English</option>
                    <option value="Physics">Physics</option>
                    <option value="Chemistry">Chemistry</option>
                    <option value="Psychology">Psychology</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="semester">
                    Semester <span style="color:#d32f2f">*</span>
                </label>
                <select id="semester" name="semester" class="form-select" required>
                    <option value="1st">1st semester</option>
                    <option value="2nd">2nd semester</option>
                    <option value="3rd">3rd semester</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="marks">
                    Marks <span style="color:#d32f2f">*</span>
                </label>
                <input 
                    type="text" 
                    id="marks" 
                    name="marks" 
                    class="form-input"
                    placeholder="Enter marks..." 
                    required
                >
            </div>
            <div class="submit-section">
                <input type="submit" class="submit-btn" value="Add/Update Result" name="sub">
                <input type="reset" class="submit-btn reset-btn" value="Reset">
            </div>
        </form>
        <!-- <div class="back">
            <a href="st_result.php"><button class="editbtn">Back to list</button></a>
        </div> -->
    </div>
</body>
</html>