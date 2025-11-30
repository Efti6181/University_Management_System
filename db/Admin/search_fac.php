<?php 
// filepath: c:\xampp\htdocs\db\Admin\search_std.php
$pageTitle = "All Faculty details";
include("../connection.php");
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
        body {
            background: #f5f7fa;
            color: #333;
        }
        header, .header {
            background: #2f2f46;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }
        header h2, .header h2 {
            font-size: 22px;
            font-weight: bold;
        }
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
        .hdinfo {
            background: #a55eea;
            padding: 12px;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
        }
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
        table tr:nth-child(even) {
            background: #f8f8f8;
        }
        a {
            color: #2d3ddf;
            text-decoration: none;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
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
    </style>
</head>
<body>
    
<div class="search_result">		
    <table class="tab_one">
        <?php 
            $min_length = 1;
            if(isset($_GET['src_faculty'])) {
                $key = $_GET['src_faculty'];
                if(strlen($key) >= $min_length){
                    $key = htmlspecialchars($key);
                    $sql = "SELECT * FROM teacher WHERE name LIKE '%$key%' OR tid LIKE '%$key%'";
                    $src_result = mysqli_query($con, $sql);
                    $count = mysqli_num_rows($src_result);
                    if($count > 0){
        ?>
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Show Profile</th>
            <th>Edit</th>
            <th>Delete</th>
            <th>Photo</th>
        </tr>
        <?php
                        while($rows = mysqli_fetch_assoc($src_result)){				
        ?>
        <tr>
            <td><?php echo htmlspecialchars($rows['name']);?></td>
            <td><?php echo htmlspecialchars($rows['tid']);?></td>
            <td><a href="admin_single_student.php?id=<?php echo urlencode($rows['tid']);?>">View Details</a></td>
            <td><a href="admin_single_student_update.php?id=<?php echo urlencode($rows['tid']);?>">Edit</a></td>
            <td><a href="delete_fac.php?tid=<?php echo urlencode($rows['tid']);?>">Delete</a></td>
            <td>
                <?php if(!empty($rows['pic'])): ?>
                    <img src="Timage/<?php echo htmlspecialchars($rows['pic']);?>" title="<?php echo htmlspecialchars($rows['name']);?>" />
                <?php else: ?>
                    <span>No Photo</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
        </table>
        <?php
                    } else {
                        echo "<h2 style='font-size:45px;text-align:center;color:#ddd;'>Opps....No result found !</h2>";
                    }
                } else {
                    echo "<h2 style='font-size:45px;text-align:center;color:#ddd;'>Opps....No result found !</h2>";
                }
            } else {
                echo "<h2 style='font-size:45px;text-align:center;color:#ddd;'>Opps....No result found !</h2>";
            }
        ?>
        <div class="back fix">
            <p style="text-align:center"><a href="view_fac.php"><button class="editbtn">Back to faculty list</button></a></p>
        </div>
</div>
</body>
</html>