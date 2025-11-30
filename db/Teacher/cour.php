<?php
session_start();
include('../connection.php');

// Check if faculty is logged in (adjust this based on your faculty authentication)
// if (!isset($_SESSION['faculty_logged_in']) && !isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

$faculty_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// Handle course selection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_course'])) {
    $course_id = mysqli_real_escape_string($con, $_POST['course_id']);
    $course_title = mysqli_real_escape_string($con, $_POST['course_title']);
    $semester = mysqli_real_escape_string($con, $_POST['semester']);
    $credit = mysqli_real_escape_string($con, $_POST['credit']);
    $session = mysqli_real_escape_string($con, $_POST['session']);
    
    // Check if already selected
    $check_query = "SELECT id FROM faculty_course_selection WHERE faculty_id = '$faculty_id' AND course_id = '$course_id' AND session = '$session'";
    $check_result = mysqli_query($con, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $message = "You have already selected this course for this session!";
        $message_type = "error";
    } else {
        $insert_query = "INSERT INTO faculty_course_selection (faculty_id, course_id, course_title, semester, credit, session) 
                        VALUES ('$faculty_id', '$course_id', '$course_title', '$semester', '$credit', '$session')";
        
        if (mysqli_query($con, $insert_query)) {
            $message = "Course selected successfully!";
            $message_type = "success";
        } else {
            $message = "Error selecting course. Please try again.";
            $message_type = "error";
        }
    }
}

// Handle course deselection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deselect_course'])) {
    $course_id = mysqli_real_escape_string($con, $_POST['course_id']);
    $session = mysqli_real_escape_string($con, $_POST['session']);
    
    $delete_query = "DELETE FROM faculty_course_selection WHERE faculty_id = '$faculty_id' AND course_id = '$course_id' AND session = '$session'";
    
    if (mysqli_query($con, $delete_query)) {
        $message = "Course deselected successfully!";
        $message_type = "success";
    } else {
        $message = "Error deselecting course. Please try again.";
        $message_type = "error";
    }
}

// Get all courses
$courses_query = "SELECT * FROM course ORDER BY session DESC, sem ASC, courseid ASC";
$courses_result = mysqli_query($con, $courses_query);

// Get faculty's selected courses
$selected_query = "SELECT course_id, session FROM faculty_course_selection WHERE faculty_id = '$faculty_id'";
$selected_result = mysqli_query($con, $selected_query);
$selected_courses = [];
while ($row = mysqli_fetch_assoc($selected_result)) {
    $selected_courses[$row['course_id'] . '_' . $row['session']] = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Course Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
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
		
        .message {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            font-weight: 500;
            border: 1px solid;
        }

        .message.success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border-color: rgba(34, 197, 94, 0.2);
        }

        .message.error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.2);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border: 1px solid #333;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: #667eea;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.1);
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: #888;
            font-size: 1rem;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .course-card {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border: 1px solid #333;
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .course-card:hover {
            transform: translateY(-4px);
            border-color: #667eea;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.1);
        }

        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .course-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .course-id {
            font-size: 0.875rem;
            color: #667eea;
            font-weight: 600;
            background: rgba(102, 126, 234, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .course-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin: 1rem 0;
            line-height: 1.4;
        }

        .course-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #888;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1rem;
            color: #ffffff;
            font-weight: 500;
        }

        .course-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }

        .btn-secondary {
            background: #333;
            color: #888;
            border: 1px solid #444;
        }

        .btn-secondary:hover {
            background: #444;
            color: #fff;
        }

        .selected-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #333 0%, #444 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .courses-grid {
                grid-template-columns: 1fr;
            }

            .course-details {
                grid-template-columns: 1fr;
            }

            .course-actions {
                flex-direction: column;
            }
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
                    <p>Faculty Courses Selection Portal</p>
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
    
    <div class="container">
        
        
        <!-- <div class="header">
            <h1>Faculty Course Management</h1>
            <p>Select courses you're interested to teach</p>
        </div> -->

        <!-- <a href="faculty-my-courses.php" class="nav-btn">
            📚 View My Selected Courses
        </a> -->

        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- <?php
        // Get statistics
        $total_courses_query = "SELECT COUNT(*) as total FROM course";
        $selected_courses_query = "SELECT COUNT(*) as selected FROM faculty_course_selection WHERE faculty_id = '$faculty_id'";
        
        $total_result = mysqli_query($con, $total_courses_query);
        $selected_result_count = mysqli_query($con, $selected_courses_query);
        
        $total_courses = mysqli_fetch_assoc($total_result)['total'];
        $selected_courses_count = mysqli_fetch_assoc($selected_result_count)['selected'];
        ?> -->

        <!-- <div class="stats">
            <div class="stat-card">
                <h3><?php echo $total_courses; ?></h3>
                <p>Total Available Courses</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $selected_courses_count; ?></h3>
                <p>Courses You Selected</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_courses - $selected_courses_count; ?></h3>
                <p>Available to Select</p>
            </div>
        </div> -->

        <div class="courses-grid">
            <?php if (mysqli_num_rows($courses_result) > 0): ?>
                <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                    <?php 
                    $course_key = $course['courseid'] . '_' . $course['session'];
                    $is_selected = isset($selected_courses[$course_key]);
                    ?>
                    <div class="course-card">
                        <?php if ($is_selected): ?>
                            <div class="selected-badge">✓ Selected</div>
                        <?php endif; ?>
                        
                        <div class="course-header">
                            <div class="course-id"><?php echo htmlspecialchars($course['courseid']); ?></div>
                        </div>
                        
                        <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                        
                        <div class="course-details">
                            <div class="detail-item">
                                <span class="detail-label">Semester</span>
                                <span class="detail-value"><?php echo htmlspecialchars($course['sem']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Credit Hours</span>
                                <span class="detail-value"><?php echo htmlspecialchars($course['credit']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Session</span>
                                <span class="detail-value"><?php echo htmlspecialchars($course['session']); ?></span>
                            </div>
                        </div>
                        
                        <div class="course-actions">
                            <?php if ($is_selected): ?>
                                <form method="POST" style="flex: 1;">
                                    <input type="hidden" name="course_id" value="<?php echo $course['courseid']; ?>">
                                    <input type="hidden" name="session" value="<?php echo $course['session']; ?>">
                                    <button type="submit" name="deselect_course" class="btn btn-danger">
                                        ✗ Deselect Course
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" style="flex: 1;">
                                    <input type="hidden" name="course_id" value="<?php echo $course['courseid']; ?>">
                                    <input type="hidden" name="course_title" value="<?php echo $course['title']; ?>">
                                    <input type="hidden" name="semester" value="<?php echo $course['sem']; ?>">
                                    <input type="hidden" name="credit" value="<?php echo $course['credit']; ?>">
                                    <input type="hidden" name="session" value="<?php echo $course['session']; ?>">
                                    <button type="submit" name="select_course" class="btn btn-primary">
                                        ✓ Select Course
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #888;">
                    <h3>No courses available</h3>
                    <p>Please contact the administrator to add courses.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
