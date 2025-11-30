<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment - Course Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;900&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background:  linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #374151;
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .form-card {
            background: white;
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border: 1px solid #fef2f2;
        }

        .form-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.8rem;
            color: #7c0c7cff;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #842164ff, #670b5fff);
            border-radius: 2px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .form-group select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #fef2f2;
            border-radius: 0.5rem;
            font-size: 1rem;
            background: white;
            color: #374151;
            transition: all 0.3s ease;
        }

        .form-group select:focus {
            outline: none;
            border-color: #861aa1ff;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .btn {
            background:  linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .courses-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .courses-table th {
            background: linear-gradient(135deg, #dc2626 0%, #f59e0b 100%);
            color: white;
            padding: 1.2rem;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
        }

        .courses-table td {
            padding: 1.2rem;
            border-bottom: 1px solid #fef2f2;
            vertical-align: middle;
        }

        .courses-table tr:hover {
            background: #fef2f2;
        }

        .exam-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .exam-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .exam-option input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #dc2626;
        }

        .exam-option label {
            font-size: 0.9rem;
            color: #374151;
            cursor: pointer;
        }

        .success-message {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
            font-weight: 600;
            margin-top: 1rem;
        }

        .error-message {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
            font-weight: 600;
            margin-top: 1rem;
        }

        .sign-out {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .sign-out:hover {
            background: rgba(255,255,255,0.3);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .form-card {
                padding: 1.5rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 1rem;
            }
            
            .exam-options {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .courses-table {
                font-size: 0.9rem;
            }
            
            .courses-table th,
            .courses-table td {
                padding: 0.8rem;
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
                    <p>Student Enrollment Portal</p>
                </div>
            </div>
            <nav class="header-nav">
            
                <a href="home.php" class="nav-btn">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
             
            </nav>
        </div>
    </header>
    <div class="container">


        <div class="form-card">
            <h2 class="form-title">Course Enrollment Form</h2>
            
            <form action="" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="session">Select Session</label>
                        <select name="session" id="session" required>
                            <option value="">Choose a session...</option>
                            <?php
                            include("../connection.php");
                            $sql = "SELECT name FROM session WHERE status=1";
                            $r = mysqli_query($con, $sql);
                            
                            if(mysqli_num_rows($r) > 0) {
                                while($row = mysqli_fetch_array($r)) {
                                    $sname = htmlspecialchars($row['name']);
                                    $selected = (isset($_POST['session']) && $_POST['session'] == $sname) ? 'selected' : '';
                                    echo "<option value='$sname' $selected>$sname</option>";
                                }
                            } else {
                                echo "<option value=''>No sessions available</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sem">Select Semester</label>
                        <select name="sem" id="sem" required>
                            <option value="">Choose a semester...</option>
                            <?php
                            $sql = "SELECT DISTINCT sem FROM course ORDER BY sem";
                            $r = mysqli_query($con, $sql);
                            
                            if(mysqli_num_rows($r) > 0) {
                                while($row = mysqli_fetch_array($r)) {
                                    $s = htmlspecialchars($row['sem']);
                                    $selected = (isset($_POST['sem']) && $_POST['sem'] == $s) ? 'selected' : '';
                                    echo "<option value='$s' $selected>Semester $s</option>";
                                }
                            } else {
                                echo "<option value=''>No semesters available</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <button type="submit" name="submit" class="btn">Search Courses</button>
                </div>
            </form>

            <?php
            if(isset($_POST['submit']) && !empty($_POST['session']) && !empty($_POST['sem'])) {
                include("../connection.php");
                $session = mysqli_real_escape_string($con, $_POST['session']);
                $sem = mysqli_real_escape_string($con, $_POST['sem']);
                $_SESSION['sname'] = $session;
                $_SESSION['sem'] = $sem;
                
                $sql = "SELECT * FROM course WHERE sem='$sem' AND session='$session' ORDER BY courseid ASC";
                $r = mysqli_query($con, $sql);
                
                if(mysqli_num_rows($r) > 0) {
                    echo '<form action="" method="post">';
                    echo '<table class="courses-table">';
                    echo '<thead><tr><th>Course ID</th><th>Course Title</th><th>Exam Type</th></tr></thead>';
                    echo '<tbody>';
                    
                    while($row = mysqli_fetch_array($r)) {
                        $cid = htmlspecialchars($row['courseid']);
                        $title = htmlspecialchars($row['title']);
                        echo "<tr>";
                        echo "<td><strong>$cid</strong></td>";
                        echo "<td>$title</td>";
                        echo "<td>";
                        echo "<input type='hidden' name='course_titles[$cid]' value='$title'>";
                        echo "<div class='exam-options'>";
                        echo "<div class='exam-option'>";
                        echo "<input type='radio' name='course_exam_data[$cid]' value='regular' id='regular_$cid' required>";
                        echo "<label for='regular_$cid'>Regular</label>";
                        echo "</div>";
                        echo "<div class='exam-option'>";
                        echo "<input type='radio' name='course_exam_data[$cid]' value='recourse' id='recourse_$cid'>";
                        echo "<label for='recourse_$cid'>Recourse</label>";
                        echo "</div>";
                        echo "<div class='exam-option'>";
                        echo "<input type='radio' name='course_exam_data[$cid]' value='retake' id='retake_$cid'>";
                        echo "<label for='retake_$cid'>Retake</label>";
                        echo "</div>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    
                    echo '</tbody></table>';
                    echo '<div style="text-align: center; margin-top: 2rem;">';
                    echo '<button type="submit" name="insert" class="btn">Complete Enrollment</button>';
                    echo '</div>';
                    echo '</form>';
                } else {
                    echo '<div class="error-message">No courses found for the selected session and semester.</div>';
                }
            }
            ?>

            <?php
            if(isset($_POST['insert']) && isset($_POST['course_exam_data']) && isset($_POST['course_titles'])) {
                $sid = $_SESSION['user_id'];
                $sem = $_SESSION['sem'];
                $session = $_SESSION['sname'];
                $course_exam_data = $_POST['course_exam_data'];
                $course_titles = $_POST['course_titles'];
                
                include("../connection.php");
                
                $success_count = 0;
                $error_count = 0;
                
                foreach($course_exam_data as $course => $exam_type) {
                    $course = mysqli_real_escape_string($con, $course);
                    $exam_type = mysqli_real_escape_string($con, $exam_type);
                    $course_name = mysqli_real_escape_string($con, $course_titles[$course]);
                    
                    $check_sql = "SELECT * FROM enroll WHERE SID='$sid' AND courseid='$course' AND sem='$sem' AND session='$session'";
                    $check_result = mysqli_query($con, $check_sql);
                    
                    if(mysqli_num_rows($check_result) == 0) {
                        $query = "INSERT INTO enroll (SID, courseid, sem, session, type, course_name) VALUES ('$sid', '$course', '$sem', '$session', '$exam_type', '$course_name')";
                        if(mysqli_query($con, $query)) {
                            $success_count++;
                        } else {
                            $error_count++;
                        }
                    }
                }
                
                if($success_count > 0) {
                    echo "<div class='success-message'>Successfully enrolled in $success_count course(s)!</div>";
                }
                if($error_count > 0) {
                    echo "<div class='error-message'>Failed to enroll in $error_count course(s). Please try again.</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
