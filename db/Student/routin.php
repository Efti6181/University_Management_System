<?php
session_start();
include('../connection.php');


$student_id = $_SESSION['user_id'];


$routine_query = "SELECT r.*, e.sem, e.session 
                  FROM routine r 
                  INNER JOIN enroll e ON r.course_name = e.course_name 
                  WHERE e.SID = '$student_id' 
                  ORDER BY 
                      CASE r.day_of_week 
                          WHEN 'Saturday' THEN 1
                          WHEN 'Sunday' THEN 2
                          WHEN 'Monday' THEN 3
                          WHEN 'Tuesday' THEN 4
                          WHEN 'Wednesday' THEN 5
                      END, r.time_slot";
$routine_result = mysqli_query($con, $routine_query);

$routine_grid = [];
$time_slots = [];
$days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday'];

if ($routine_result && mysqli_num_rows($routine_result) > 0) {
    while ($row = mysqli_fetch_assoc($routine_result)) {
        $routine_grid[$row['time_slot']][$row['day_of_week']] = $row;
        if (!in_array($row['time_slot'], $time_slots)) {
            $time_slots[] = $row['time_slot'];
        }
    }
}

// Sort time slots
sort($time_slots);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Routine - Student Portal</title>
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
<body>
    <div class="container">
        
        <div class="info-banner">
            <h2>Enrolled Courses Schedule</h2>
            <!-- <p>Showing only courses you are currently enrolled in</p> -->
        </div>

        <div class="routine-container">
            <?php if (empty($time_slots)): ?>
                <div class="no-routine">
                    <h3>No Routine Available</h3>
                    <p>You don't have any scheduled classes yet. Please enroll in courses to see your routine.</p>
                </div>
            <?php else: ?>
                <!-- Timetable grid layout -->
                <table class="timetable">
                    <thead>
                        <tr>
                            <th>⏰ Time</th>
                            <?php foreach ($days as $day): ?>
                                <th><?php echo $day; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($time_slots as $time): ?>
                            <tr>
                                <td class="time-cell"><?php echo htmlspecialchars($time); ?></td>
                                <?php foreach ($days as $day): ?>
                                    <td class="class-cell">
                                        <?php if (isset($routine_grid[$time][$day])): ?>
                                            <?php $class = $routine_grid[$time][$day]; ?>
                                            <div class="class-info">
                                                <div class="course-name"><?php echo htmlspecialchars($class['course_name']); ?></div>
                                                <div class="course-details">
                                                    <!-- <?php echo htmlspecialchars($class['sem']); ?> | <?php echo htmlspecialchars($class['session']); ?> -->
                                                </div>
                                                <div class="room-info"> 🏫 Room: <?php echo htmlspecialchars($class['room_no']); ?></div>
                                            </div>
                                        <?php else: ?>
                                            <div class="empty-cell">-</div>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
