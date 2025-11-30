<?php
session_start();
include('../connection.php');

// Check if faculty is logged in (adjust this based on your faculty authentication)
if (!isset($_SESSION['faculty_logged_in']) && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$faculty_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $selection_id = mysqli_real_escape_string($con, $_POST['selection_id']);
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    
    $update_query = "UPDATE faculty_course_selection SET status = '$new_status' WHERE id = '$selection_id' AND faculty_id = '$faculty_id'";
    
    if (mysqli_query($con, $update_query)) {
        $message = "Course status updated successfully!";
        $message_type = "success";
    } else {
        $message = "Error updating status. Please try again.";
        $message_type = "error";
    }
}

// Handle course removal
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_course'])) {
    $selection_id = mysqli_real_escape_string($con, $_POST['selection_id']);
    
    $delete_query = "DELETE FROM faculty_course_selection WHERE id = '$selection_id' AND faculty_id = '$faculty_id'";
    
    if (mysqli_query($con, $delete_query)) {
        $message = "Course removed successfully!";
        $message_type = "success";
    } else {
        $message = "Error removing course. Please try again.";
        $message_type = "error";
    }
}

// Get faculty's selected courses
$courses_query = "SELECT * FROM faculty_course_selection WHERE faculty_id = '$faculty_id' ORDER BY session DESC, semester ASC, course_id ASC";
$courses_result = mysqli_query($con, $courses_query);

// Get faculty info (assuming faculty table exists)
$faculty_query = "SELECT name FROM student WHERE ID = '$faculty_id'"; // Adjust table name as needed
$faculty_result = mysqli_query($con, $faculty_query);
$faculty_data = mysqli_fetch_assoc($faculty_result);
$faculty_name = $faculty_data['name'] ?? 'Faculty Member';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Selected Courses</title>
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: #000000ff;
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
            color: #000000ff;
            font-size: 1rem;
        }

        .courses-table {
            background: #ffffff;
            border: 1px solid #333;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem 2rem;
        }

        .table-header h3 {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .table-content {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1.5rem;
            text-align: left;
            border-bottom: 1px solid #333;
        }

        th {
            background: #1a1a1a;
            color: #888;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            color: #000000ff;
        }

        tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        .course-title {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.25rem;
        }

        .course-id {
            font-size: 0.875rem;
            color: #000000ff;
            font-weight: 500;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-selected {
            background: rgba(102, 126, 234, 0.2);
            color: #000000ff;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .status-teaching {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-completed {
            background: rgba(156, 163, 175, 0.2);
            color: #9ca3af;
            border: 1px solid rgba(156, 163, 175, 0.3);
        }

        .action-form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .status-select {
            padding: 0.5rem;
            background: #333;
            border: 1px solid #444;
            border-radius: 6px;
            color: #ffffff;
            font-size: 0.875rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #000000ff;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #000000ff;
        }

        .empty-state p {
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-content {
                font-size: 0.875rem;
            }

            th, td {
                padding: 1rem 0.5rem;
            }

            .action-form {
                flex-direction: column;
                gap: 0.25rem;
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
                    <p>My Selected Courses Portal</p>
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
           <!-- Header -->


        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php
    
        $total_selected = mysqli_num_rows($courses_result);
        
   
        mysqli_data_seek($courses_result, 0);
        $teaching_count = 0;
        $completed_count = 0;
        $selected_count = 0;
        
        while ($row = mysqli_fetch_assoc($courses_result)) {
            switch ($row['status']) {
                case 'teaching':
                    $teaching_count++;
                    break;
                case 'completed':
                    $completed_count++;
                    break;
                default:
                    $selected_count++;
                    break;
            }
        }
        
    
        mysqli_data_seek($courses_result, 0);
        ?>

        
        <?php if ($total_selected > 0): ?>
            <div class="courses-table">
               
                <div class="table-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Semester</th>
                                <th>Credits</th>
                                <th>Session</th>
                                <th>Status</th>
                                <th>Selected Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                                <tr>
                                    <td>
                                        <div class="course-title"><?php echo htmlspecialchars($course['course_title']); ?></div>
                                        <div class="course-id"><?php echo htmlspecialchars($course['course_id']); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($course['semester']); ?></td>
                                    <td><?php echo htmlspecialchars($course['credit']); ?></td>
                                    <td><?php echo htmlspecialchars($course['session']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $course['status']; ?>">
                                            <?php echo ucfirst($course['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($course['selected_date'])); ?></td>
                                    <td>
                                        <div class="action-form">
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="selection_id" value="<?php echo $course['id']; ?>">
                                                <select name="status" class="status-select">
                                                    <option value="selected" <?php echo $course['status'] == 'selected' ? 'selected' : ''; ?>>Selected</option>
                                                    <option value="teaching" <?php echo $course['status'] == 'teaching' ? 'selected' : ''; ?>>Teaching</option>
                                                    <option value="completed" <?php echo $course['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                </select>
                                                <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                                            </form>
                                            <form method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to remove this course?')">
                                                <input type="hidden" name="selection_id" value="<?php echo $course['id']; ?>">
                                                <button type="submit" name="remove_course" class="btn btn-danger">Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="courses-table">
                <div class="empty-state">
                    <h3>No Courses Selected</h3>
                    <p>You haven't selected any courses yet. Start by browsing available courses.</p>
                    <a href="faculty-course-management.php" class="btn btn-primary">Browse Courses</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
