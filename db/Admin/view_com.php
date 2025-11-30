<?php
session_start();
include('../connection.php');

// Check if admin is logged in (adjust this based on your admin authentication)
// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: admin-login.php");
//     exit();
// }

// Handle status update
if (isset($_POST['update_status'])) {
    $complaint_id = mysqli_real_escape_string($con, $_POST['complaint_id']);
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    
    $update_query = "UPDATE complaints SET status = '$new_status' WHERE id = '$complaint_id'";
    mysqli_query($con, $update_query);
}

// Fetch all complaints with student information
$query = "SELECT c.id, c.student_id, c.subject, c.complaint, c.created_at, c.status, s.name, s.dept, s.batch 
          FROM complaints c 
          JOIN student s ON c.student_id = s.ID 
          ORDER BY c.created_at DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints - Admin Panel</title>
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

        .admin-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 16px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .stat-card {
            flex: 1;
            min-width: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 14px;
            opacity: 0.9;
        }

        .complaints-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .complaints-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        .complaints-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .complaints-table tr:hover {
            background-color: #f8f9fa;
        }

        .complaint-text {
            max-width: 300px;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-resolved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-select {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 12px;
        }

        .update-btn {
            padding: 5px 12px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 5px;
        }

        .update-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
        }

        .no-complaints {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 20px;
            }
            
            .complaints-table {
                font-size: 12px;
            }
            
            .complaints-table th,
            .complaints-table td {
                padding: 10px 5px;
            }
            
            .complaint-text {
                max-width: 200px;
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
                    <p>View Complaint Portal</p>
                </div>
            </div>
            <nav class="header-nav">
                <a href="admin.php" class="nav-btn">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
                <!-- <a href="admin.php" class="nav-btn">
                    <span>🔙</span>
                    <span>Back To List</span>
                </a> -->
            </nav>
        </div>
    </header>
    <!-- <div class="admin-container">
        <div class="header">
            <h1>Student Complaints</h1>
            <p>Manage and review all student complaints</p>

                            <a href="admin.php" class="nav-btn">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a> -->
        </div>

        <?php
        // Get complaint statistics
        $total_query = "SELECT COUNT(*) as total FROM complaints";
        $pending_query = "SELECT COUNT(*) as pending FROM complaints WHERE status = 'pending'";
        $resolved_query = "SELECT COUNT(*) as resolved FROM complaints WHERE status = 'resolved'";
        
        $total_result = mysqli_query($con, $total_query);
        $pending_result = mysqli_query($con, $pending_query);
        $resolved_result = mysqli_query($con, $resolved_query);

        $total_count = mysqli_fetch_assoc($total_result)['total'];
        $pending_count = mysqli_fetch_assoc($pending_result)['pending'];
        $resolved_count = mysqli_fetch_assoc($resolved_result)['resolved'];
        ?>

        <div class="stats">
            <div class="stat-card">
                <h3><?php echo $total_count; ?></h3>
                <p>Total Complaints</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $pending_count; ?></h3>
                <p>Pending Complaints</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $resolved_count; ?></h3>
                <p>Resolved Complaints</p>
            </div>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="complaints-table">
                <thead>
                    <tr>
                        <th>Student Info</th>
                        <th>Subject</th>
                        <th>Complaint</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                <small>ID: <?php echo htmlspecialchars($row['student_id']); ?></small><br>
                                <small><?php echo htmlspecialchars($row['dept']); ?> - <?php echo htmlspecialchars($row['batch']); ?></small>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['subject']); ?></strong>
                            </td>
                            <td class="complaint-text">
                                <?php echo htmlspecialchars($row['complaint']); ?>
                            </td>
                            <td>
                                <?php echo date('M d, Y', strtotime($row['created_at'])); ?><br>
                                <small><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" style="display: inline-block;">
                                    <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                    <select name="status" class="status-select">
                                        <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="resolved" <?php echo $row['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                    </select>
                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-complaints">
                <p>No complaints found.</p>
            </div>
        <?php endif; ?>

        <!-- <a href="admin.php" class="back-btn">Back to Dashboard</a> -->
    </div>
</body>
</html>
