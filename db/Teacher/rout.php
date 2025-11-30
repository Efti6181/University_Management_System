<?php
session_start();


include("../connection.php");

$faculty_id = $_SESSION['user_id'];


$routine_query = "SELECT 
    r.course_name,
    r.time_slot,
    r.room_no,
    r.day_of_week,
    r.semester as routine_semester,
    fcs.semester,
    fcs.credit,
    fcs.session
FROM routine r
INNER JOIN faculty_course_selection fcs 
    ON r.course_name = fcs.course_title 
    AND fcs.faculty_id = '$faculty_id'
ORDER BY 
    CASE r.day_of_week 
            WHEN 'Saturday' THEN 1
        WHEN 'Sunday' THEN 2
        WHEN 'Monday' THEN 3
        WHEN 'Tuesday' THEN 4
        WHEN 'Wednesday' THEN 5

    END, 
    r.time_slot";

$routine_result = mysqli_query($con, $routine_query);

$routine_grid = [];
$time_slots = [];
$days = [ 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday'];
$course_count = 0;

if($routine_result && mysqli_num_rows($routine_result) > 0) {
    while($row = mysqli_fetch_assoc($routine_result)) {
        $routine_grid[$row['day_of_week']][$row['time_slot']] = $row;
        if(!in_array($row['time_slot'], $time_slots)) {
            $time_slots[] = $row['time_slot'];
        }
        $course_count++;
    }
}

// Sort time slots
sort($time_slots);

$selected_courses_query = "SELECT COUNT(*) as total FROM faculty_course_selection WHERE faculty_id = '$faculty_id'";
$selected_result = mysqli_query($con, $selected_courses_query);
$selected_count = 0;
if($selected_result) {
    $selected_row = mysqli_fetch_assoc($selected_result);
    $selected_count = $selected_row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Schedule </title>
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
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            /* border-radius: 15px; */
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
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

        .info-banner {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }

        .info-banner h2 {
            color: #2c3e50;
            font-size: 1.3rem;
            margin-bottom: 5px;
        }

        .info-banner p {
            color: #666;
            font-size: 0.95rem;
        }

        .routine-container {
            padding: 30px;
            overflow-x: auto;
        }

        .no-routine {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-routine h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        /* Timetable grid layout */
        .timetable {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .timetable th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 10px;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .timetable th:first-child {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            width: 120px;
        }

        .timetable td {
            padding: 15px 10px;
            text-align: center;
            border: 1px solid #e9ecef;
            vertical-align: middle;
            height: 100px;
            position: relative;
        }

        .timetable tr:nth-child(even) {
            background: #f8f9fa;
        }

        .timetable tr:hover {
            background: #e3f2fd;
        }

        .time-cell {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .class-cell {
            transition: all 0.3s ease;
        }

        .class-cell:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .class-info {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            line-height: 1.4;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 4px;
        }

        .course-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .course-details {
            font-size: 0.75rem;
            opacity: 0.95;
        }

        .room-info {
            font-size: 1rem;
            opacity: 0.9;
        }

        .empty-cell {
            color: #999;
            font-style: italic;
            font-size: 0.9rem;
        }

        .back-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .routine-container {
                padding: 15px;
            }

            .timetable th,
            .timetable td {
                padding: 8px 5px;
                font-size: 0.8rem;
            }

            .timetable th:first-child {
                width: 80px;
            }

            .class-info {
                padding: 5px;
                font-size: 0.75rem;
            }

            .back-button {
                bottom: 20px;
                right: 20px;
                padding: 12px 20px;
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
                    <p>My  class schedule Portal</p>
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
            <div class="info-banner">
                <h2>📋 Weekly Teaching Timetable</h2>
                <!-- <p>Your complete class schedule with course details</p> -->
            </div>

            <?php if($selected_count == 0): ?>
                <div class="no-schedule">
                    <h3>No Courses Selected</h3>
                    <!-- <p>You haven't selected any courses to teach yet.</p> -->
                    <!-- <p>Please select courses from the course management page to view your schedule.</p> -->
                    
                </div>
            <?php elseif(empty($time_slots)): ?>
                <div class="no-schedule">
                    <h3>No Schedule Available</h3>
                    <!-- <p>Your teaching schedule is not yet available in the system.</p> -->
                    <!-- <p>The routine may not be created yet or your selected courses don't have scheduled classes.</p> -->
                  
                </div>
            <?php else: ?>
                <div class="timetable-wrapper">
                    <table class="timetable">
                        <thead>
                            <tr>
                                <th>⏰ Time</th>
                                <?php foreach($days as $day): ?>
                                    <th><?php echo htmlspecialchars($day); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($time_slots as $time): ?>
                                <tr>
                                    <td class="time-cell"><?php echo $time; ?></td>
                                    <?php foreach($days as $day): ?>
                                        <td class="class-cell">
                                            <?php if(isset($routine_grid[$day][$time])): ?>
                                                <?php $class = $routine_grid[$day][$time]; ?>
                                                <div class="class-info">
                                                    <div class="course-name">
                                                        <?php echo htmlspecialchars($class['course_name']); ?>
                                                    </div>
                                                    <div class="course-details">
                                                        <div class="detail-row">
                                                            <span class="detail-label"></span>
                                                            <!-- <span class="detail-value"><?php echo htmlspecialchars($class['semester']); ?></span> -->
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="room-info">
                                                        🏫 Room: <?php echo htmlspecialchars($class['room_no']); ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="empty-cell">—</div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
