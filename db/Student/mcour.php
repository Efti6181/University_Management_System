<?php
   session_start();
   if($_SESSION['student_login_status']!="loged in" and ! isset($_SESSION['user_id']) )
    header("Location:../login.php");
   //Sign Out code
   if(isset($_GET['sign']) and $_GET['sign']=="out") {
	$_SESSION['student_login_status']="loged out";
	unset($_SESSION['user_id']);
   header("Location:../index.php");    
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Enrolled Courses</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: var(--foreground);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* .header {
            background: linear-gradient(135deg, var(--primary) 0%, #0e7490 100%);
            color: var(--primary-foreground);
            padding: 2rem 0;
            text-align: center;
            box-shadow: 0 4px 20px rgba(8, 145, 178, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h1 {
            font-family: 'Work Sans', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        } */

            .h1 {
            font-family: 'Work Sans', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .search-section {
            background: var(--card);
            border-radius: var(--radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border);
        }

        .search-section h2 {
            font-family: 'Work Sans', sans-serif;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .form-group label {
            font-weight: 600;
            color: var(--foreground);
            min-width: 120px;
        }

        .form-group select {
            flex: 1;
            min-width: 200px;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            background: var(--input);
            color: var(--foreground);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--ring);
        }

        .search-btn {
            background: linear-gradient(135deg, var(--primary) 0%, #0e7490 100%);
            color: var(--primary-foreground);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(8, 145, 178, 0.3);
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(8, 145, 178, 0.4);
        }

        .results-section {
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border);
        }

        .results-header {
            background: linear-gradient(135deg, var(--primary) 0%, #0e7490 100%);
            color: var(--primary-foreground);
            padding: 1.5rem 2rem;
        }

        .results-header h3 {
            font-family: 'Work Sans', sans-serif;
            font-weight: 600;
            font-size: 1.3rem;
        }

        .table-container {
            overflow-x: auto;
        }

        .smart-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--background);
        }

        .smart-table th {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--foreground);
            font-family: 'Work Sans', sans-serif;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid var(--border);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .smart-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .smart-table tbody tr:nth-child(even) {
            background: var(--muted);
        }

        .smart-table tbody tr:hover {
            background: rgba(8, 145, 178, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .course-id {
            font-weight: 600;
            color: var(--primary);
            font-family: 'Work Sans', sans-serif;
        }

        .course-title {
            font-weight: 500;
            color: var(--foreground);
        }

        .semester-badge {
            background: linear-gradient(135deg, var(--accent) 0%, #d97706 100%);
            color: var(--accent-foreground);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }

        .credit-badge {
            background: linear-gradient(135deg, var(--primary) 0%, #0e7490 100%);
            color: var(--primary-foreground);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            text-align: center;
            min-width: 40px;
            display: inline-block;
        }

        .exam-type {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .exam-regular {
            background: #dcfce7;
            color: #166534;
        }

        .exam-retake {
            background: #fef3c7;
            color: #92400e;
        }

        .exam-recourse {
            background: #fee2e2;
            color: #991b1b;
        }

        .total-row {
            background: linear-gradient(135deg, var(--primary) 0%, #0e7490 100%);
            color: var(--primary-foreground);
            font-weight: 600;
        }

        .total-row td {
            border: none;
            font-size: 1.1rem;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--muted-foreground);
        }

        .no-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .container {
                padding: 1rem;
            }

            .form-group {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group label {
                min-width: auto;
            }

            .smart-table {
                font-size: 0.9rem;
            }

            .smart-table th,
            .smart-table td {
                padding: 0.75rem 0.5rem;
            }

            .table-container {
                border-radius: var(--radius);
            }
        }

        @media (max-width: 480px) {
            .smart-table thead {
                display: none;
            }

            .smart-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid var(--border);
                border-radius: var(--radius);
                background: var(--card);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .smart-table tbody td {
                display: block;
                text-align: right;
                padding: 0.75rem;
                border: none;
                border-bottom: 1px solid var(--border);
            }

            .smart-table tbody td:last-child {
                border-bottom: none;
            }

            .smart-table tbody td:before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: var(--primary);
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
                    <p>View Enrolled Course Portal</p>
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

<!-- <div class="header">
    <h1>My Enrolled Courses</h1>
    <p>View your course enrollment details by session</p>
</div> -->

<div class="container">
    <div class="search-section">
        <h1>My Enrolled Courses</h1>
        <!-- <h2>Select Session</h2> -->
        <form action="" method="post">
            <div class="form-group">
                <label for="session">Academic Session:</label>
                <select name="session" id="session" required>
                    <option value="">Choose a session...</option>
                    <?php
                    include("../connection.php");
                    $sql = "SELECT name FROM session WHERE status=1 ORDER BY name DESC";
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
                <button type="submit" name="submit" class="search-btn">Search Courses</button>
            </div>
        </form>
    </div>

    <?php
    if(isset($_POST['submit']) && !empty($_POST['session'])) {
        include("../connection.php");
        $sid = $_SESSION['user_id'];
        $session = mysqli_real_escape_string($con, $_POST['session']);
        
        $sql = "SELECT * FROM enroll, course WHERE enroll.courseid=course.courseid AND enroll.session='$session' AND enroll.SID='$sid' ORDER BY course.sem, course.courseid";
        $r = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($r) > 0) {
            echo '<div class="results-section">';
            echo '<div class="results-header">';
            echo '<h3>Enrolled Courses for ' . htmlspecialchars($session) . '</h3>';
            echo '</div>';
            echo '<div class="table-container">';
            echo '<table class="smart-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Course ID</th>';
            echo '<th>Course Title</th>';
            echo '<th>Semester</th>';
            echo '<th>Credit</th>';
            echo '<th>Exam Type</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            $totalc = 0;
            while($row = mysqli_fetch_array($r)) {
                $id = htmlspecialchars($row['courseid']);
                $title = htmlspecialchars($row['title']);
                $type = htmlspecialchars($row['type']);
                $sem = htmlspecialchars($row['sem']);
                $credit = (int)$row['credit'];
                $totalc += $credit;
                
                $examClass = 'exam-' . strtolower($type);
                
                echo '<tr>';
                echo '<td data-label="Course ID: " class="course-id">' . $id . '</td>';
                echo '<td data-label="Course Title: " class="course-title">' . $title . '</td>';
                echo '<td data-label="Semester: "><span class="semester-badge">Semester ' . $sem . '</span></td>';
                echo '<td data-label="Credit: "><span class="credit-badge">' . $credit . '</span></td>';
                echo '<td data-label="Exam Type: "><span class="exam-type ' . $examClass . '">' . $type . '</span></td>';
                echo '</tr>';
            }
            
            echo '<tr class="total-row">';
            echo '<td colspan="3"><strong>Total Credits</strong></td>';
            echo '<td colspan="2"><strong>' . $totalc . '</strong></td>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="results-section">';
            echo '<div class="no-results">';
            echo '<i>📚</i>';
            echo '<h3>No courses found</h3>';
            echo '<p>You are not enrolled in any courses for the selected session.</p>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</div>

</body>
</html>
