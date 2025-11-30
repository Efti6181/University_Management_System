<?php
// filepath: c:\xampp\htdocs\db\Admin\view_std.php
session_start();
include("../connection.php");

$pageTitle = "All student details";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <style>

		/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Body */
body {
    background: #f5f7fa;
    color: #333;
}

/* Top Header */
/* header, .header {
    background: #2f2f46;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #fff;
} */

header h2, .header h2 {
    font-size: 22px;
    font-weight: bold;
}

/* Navigation right side (admin, options, logout) */
.header-right {
    display: flex;
    gap: 15px;
    font-size: 14px;
    align-items: center;
}

.header-right a {
    color: #fff;
    text-decoration: none;
}

/* Page Title Bar */
.hdinfo {
    background: #a55eea;
    padding: 12px;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
}

/* Search Bar */
.search {
    float: right;
    margin-top: -40px;
    margin-right: 20px;
}

.search input[type="text"] {
    padding: 7px 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    outline: none;
}

.search input[type="submit"] {
    padding: 7px 15px;
    border: none;
    background: #5d3fd3;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}

.search input[type="submit"]:hover {
    background: #4427a5;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px auto;
    background: #fff;
    box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
}

table th {
    background: #009688;
    color: #fff;
    padding: 12px;
    text-align: left;
}

table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

/* Alternating Row Colors */
table tr:nth-child(even) {
    background: #f8f8f8;
}

/* Links */
a {
    color: #2d3ddf;
    text-decoration: none;
    font-weight: 500;
}

a:hover {
    text-decoration: underline;
}

/* Photo Column */
table td img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

table td span {
    font-size: 13px;
    color: #999;
}

        /* Add your styles here */
        /* .all_student { max-width: 900px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 2rem; }
        .hdinfo h3 { margin-bottom: 1rem; }
        .search { margin-bottom: 1.5rem; }
        .tab_one { width: 100%; border-collapse: collapse; }
        .tab_one th, .tab_one td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .tab_one th { background: #f4f6f9; }
        img { border-radius: 8px; } */

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

    <!-- Header -->
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
                <!-- <a href="#" class="nav-btn">
                    <span>🏠</span>
                    <span>Home</span>
                </a> -->
                <a href="admin.php" class="nav-btn">
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
<div class="all_student">
    <div class="search_st">
        <div class="hdinfo"><h3>All Registered Student List</h3></div>
        <div class="search">
            <form action="search_std.php" method="GET">
                <input type="text" name="src_student" placeholder="search student" />
                <input type="submit" value="Search" />
            </form>
        </div>
    </div>
    <?php
        if(isset($_REQUEST['res'])){
            if($_REQUEST['res']==1){
                echo "<h3 style='color:green;text-align:center;margin:0;padding:10px;'>Data deleted successfully</h3>";
            }
        }
    ?>
    <table class="tab_one">
        <tr>
            <th>SL</th>
            <th>Name</th>
            <th>ID</th>
            <th>Batch</th>
            <th>Department</th>
            <th>Mobile</th>
            <th>Show Profile</th>
            <th>Edit</th>
            <th>Delete</th>
            <th>Photo</th>
        </tr>
        <?php 
        $i=0;
        $result = mysqli_query($con, "SELECT * FROM student");
        while($rows = mysqli_fetch_assoc($result)){
            $i++;
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo htmlspecialchars($rows['name']);?></td>
            <td><?php echo htmlspecialchars($rows['ID']);?></td>
            <td><?php echo htmlspecialchars($rows['batch']);?></td>
            <td><?php echo htmlspecialchars($rows['dept']);?></td>
            <td><?php echo htmlspecialchars($rows['mobileno']);?></td>
<td><a href="std_details.php?vr=<?php echo urlencode($rows['ID']); ?>">View Details</a></td>
            <!-- <td><a href="std_details.php?vr=<?php echo urlencode($rows['ID']); ?>&vn=<?php echo urlencode($rows['name']); ?>">View Details</a></td> -->
            <!-- <td><a href="admin_single_student_update.php?id=<?php echo urlencode($rows['ID']);?>">Edit</a></td> -->
             <td><a href="std_edit.php?id=<?php echo urlencode($rows['ID']);?>" class="btn btn-sm btn-primary">Edit</a></td>
            <td><a href="delete_std.php?id=<?php echo urlencode($rows['ID']);?>">Delete</a></td>
            <td>
                <?php if(!empty($rows['pic'])): ?>
                    <img src="Simage/<?php echo htmlspecialchars($rows['pic']);?>" width="50" height="50" alt="<?php echo htmlspecialchars($rows['pic']);?>" />
                <?php else: ?>
                    <span>No Photo</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>