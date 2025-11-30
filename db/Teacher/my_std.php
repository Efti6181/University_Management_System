<?php
session_start();
include('../connection.php');



$faculty_id = $_SESSION['user_id'];
$selected_session = $_GET['session'] ?? '';

// Get faculty's selected courses for filter dropdown
$faculty_courses_query = "SELECT DISTINCT fcs.course_id, fcs.course_title, fcs.session, fcs.semester 
                         FROM faculty_course_selection fcs 
                         WHERE fcs.faculty_id = '$faculty_id' 
                         ORDER BY fcs.session DESC, fcs.semester ASC, fcs.course_id ASC";
$faculty_courses_result = mysqli_query($con, $faculty_courses_query);

$courses_with_students_query = "SELECT DISTINCT 
                    fcs.course_id,
                    fcs.course_title,
                    fcs.session,
                    fcs.semester as course_semester,
                    fcs.credit
                   FROM faculty_course_selection fcs
                   JOIN enroll e ON fcs.course_id = e.courseid AND fcs.session = e.session
                   WHERE fcs.faculty_id = '$faculty_id'";

// Add session filter if selected
if (!empty($selected_session)) {
    $courses_with_students_query .= " AND e.session = '" . mysqli_real_escape_string($con, $selected_session) . "'";
}

$courses_with_students_query .= " ORDER BY fcs.session DESC, fcs.semester ASC, fcs.course_id ASC";
$courses_result = mysqli_query($con, $courses_with_students_query);

// Get statistics
$total_students_query = "SELECT COUNT(DISTINCT s.ID) as total 
                        FROM faculty_course_selection fcs
                        JOIN enroll e ON fcs.course_id = e.courseid AND fcs.session = e.session
                        JOIN student s ON e.SID = s.ID
                        WHERE fcs.faculty_id = '$faculty_id'";
$total_students_result = mysqli_query($con, $total_students_query);
$total_students = mysqli_fetch_assoc($total_students_result)['total'];

$total_courses_query = "SELECT COUNT(*) as total FROM faculty_course_selection WHERE faculty_id = '$faculty_id'";
$total_courses_result = mysqli_query($con, $total_courses_query);
$total_courses = mysqli_fetch_assoc($total_courses_result)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students - Faculty Portal</title>
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
            /* max-width: 1400px; */
            margin: 0 auto;
            /* padding: 2rem; */
        }


        
     

        /* Added course-wise table styling */
        .course-section {
            margin-bottom: 3rem;
        }

        .course-table {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border: 1px solid #333;
            /* border-radius: 16px; */
            /* overflow: hidden; */
        }

        .course-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* flex-wrap: wrap; */
            gap: 1rem;
        }

        .course-info h3 {
            color: black;
            font-size: 1.5rem;
            /* font-weight: 600; */
            margin-bottom: 0.25rem;
        }

        .course-details {
            color: black;
            /* font-size: 0.9rem; */
        }

        .student-count {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
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
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid #333;
        }

        th {
            background: #2a2a2a;
            color: #ffffffff;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            color: #ffffff;
            font-size: 0.95rem;
        }

        tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        .student-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .student-name {
            font-weight: 600;
            color: #ffffff;
        }

        .student-id {
            font-size: 0.8rem;
            color: #ffffffff;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-regular {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .badge-recourse {
            background: rgba(251, 191, 36, 0.1);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.2);
        }

        .badge-retake {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .no-students {
            text-align: center;
            padding: 3rem;
            color: #888;
        }

        .no-students h3 {
            margin-bottom: 1rem;
            color: #667eea;
        }

        .no-courses {
            text-align: center;
            padding: 4rem 2rem;
            color: #888;
        }

        .no-courses h3 {
            margin-bottom: 1rem;
            color: #667eea;
            font-size: 1.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .nav-section {
                flex-direction: column;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .course-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .table-content {
                font-size: 0.875rem;
            }

            th, td {
                padding: 0.75rem 1rem;
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
                    <p> Selected Courses Enrolled Student List Portal</p>
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
        


          
        <!-- Course-wise student tables -->
        <?php if (mysqli_num_rows($courses_result) > 0): ?>
            <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                <?php
                // Get students for this specific course
                $students_query = "SELECT DISTINCT 
                            s.ID as student_id,
                            s.name as student_name,
                            s.dept as department,
                            s.batch,
                            s.mobileno,
                            si.semester as current_semester,
                            si.section,
                            si.email,
                            e.type as exam_type
                           FROM enroll e
                           JOIN student s ON e.SID = s.ID
                           LEFT JOIN st_info si ON s.ID = si.ID
                           WHERE e.courseid = '" . mysqli_real_escape_string($con, $course['course_id']) . "'
                           AND e.session = '" . mysqli_real_escape_string($con, $course['session']) . "'
                           ORDER BY s.name ASC";
                
                $students_result = mysqli_query($con, $students_query);
                $student_count = mysqli_num_rows($students_result);
                ?>
                
                <div class="course-section">
                    <div class="course-table">
                        <div class="course-header">
                            <div class="course-info">
                                <h3><?php echo htmlspecialchars($course['course_title']); ?></h3>
                                <div class="course-details">
                                    Course Code: <?php echo htmlspecialchars($course['course_id']); ?> • 
                                    Semester <?php echo htmlspecialchars($course['course_semester']); ?> • 
                                    <!-- <?php echo htmlspecialchars($course['credit']); ?> Credits •  -->
                                    Session: <?php echo htmlspecialchars($course['session']); ?>
                                </div>
                            </div>
                            <div class="student-count">
                                <?php echo $student_count; ?> Student<?php echo $student_count != 1 ? 's' : ''; ?>
                            </div>
                        </div>
                        
                        <div class="table-content">
                            <?php if ($student_count > 0): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Student ID</th>
                                            <th>Batch</th>
                                            <th>Section</th>
                                            <th>Exam Type</th>
                                            <!-- <th>Contact</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                                            <tr>
                                                <td>
                                                    <div class="student-info">
                                                        <span class="student-name"><?php echo htmlspecialchars($student['student_name']); ?></span>
                                                        <!-- <span class="student-id">ID: <?php echo htmlspecialchars($student['student_id']); ?></span> -->
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                                <td><?php echo htmlspecialchars($student['batch']); ?></td>
                                                <td><?php echo htmlspecialchars($student['section'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo strtolower($student['exam_type']); ?>">
                                                        <?php echo htmlspecialchars($student['exam_type']); ?>
                                                    </span>
                                                </td>
                                                <!-- <td>
                                                    <div class="contact-info">
                                                        <span><?php echo htmlspecialchars($student['mobileno']); ?></span>
                                                        <?php if (!empty($student['email'])): ?>
                                                            <span style="font-size: 0.8rem; color: #888;"><?php echo htmlspecialchars($student['email']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td> -->
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="no-students">
                                    <h3>📭 No Students Enrolled</h3>
                                    <p>No students are currently enrolled in this course.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-courses">
                <h3>📚 No Courses Found</h3>
                <p>No courses with student enrollments found for the selected criteria.</p>
                <p>Make sure you have selected courses in the Course Management section and students have enrolled.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
