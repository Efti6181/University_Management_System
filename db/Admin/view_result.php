<?php
// filepath: c:\xampp\htdocs\db\Admin\view_result.php
session_start();
include("../connection.php");

$pageTitle = "Student Result";

// Get student info from request
if (isset($_REQUEST['vr']) && isset($_REQUEST['vn'])) {
    $stid = $_REQUEST['vr'];
    $name = $_REQUEST['vn'];
} else {
    echo "<p style='color:red;text-align:center'>No student selected.</p>";
    exit();
}

// Custom function: credit hour
function credit_hour($x){
    if($x=="DBMS") return 3;
    elseif($x == "DBMS Lab") return 1;
    elseif($x == "Mathematics") return 4;
    elseif($x == "Programming") return 3;
    elseif($x == "Programming Lab") return 1;
    elseif($x == "English") return 4;
    elseif($x == "Physics") return 3;
    elseif($x == "Chemistry") return 3;
    elseif($x == "Psychology") return 3;
    else return 0;
}
// Custom function: grade point
function grade_point($gd){
    if($gd<40) return 0;
    elseif($gd>=40 && $gd<50) return 1;
    elseif($gd>=50 && $gd<60) return 2;
    elseif($gd>=60 && $gd<70) return 3;
    elseif($gd>=70 && $gd<80) return 3.5;
    elseif($gd>=80 && $gd<=100) return 4;
    else return 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
        }
        .header {
            width: 100vw;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 2rem;
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
        .all_student {
            max-width: 900px;
            margin: 120px auto 2rem auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        .student-info {
            background: #f3e6fa;
            color: #6a1b9a;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1rem;
        }
        .editbtn {
            background: #2196F3;
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s;
        }
        .editbtn:hover {
            background: #1976d2;
            transform: scale(1.05);
        }
        .tab_two {
            text-align: center;
            width: 85%;
            margin: 0 auto 2rem auto;
            border-collapse: collapse;
            box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
        }
        .tab_two th {
            background: #009688;
            color: #fff;
            padding: 12px;
        }
        .tab_two td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .tab_two tr:nth-child(even) {
            background: #f8f8f8;
        }
        .tab_two tr:last-child td {
            font-weight: bold;
        }
        .msg {
            text-align: center;
            padding: 10px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .msg.success {
            color: #43a047;
        }
        .msg.error {
            color: #d32f2f;
        }
    </style>
</head>
<body>
    <!-- Header (full width, fixed at top) -->
    <header class="header">
        <div class="header-content">
            <div class="university-logo">
                <div class="logo-icon">🎓</div>
                <div class="university-info">
                    <h1>Premier University Chittagong</h1>
                    <p>Student Result Portal</p>
                </div>
            </div>
            <nav class="header-nav">
                <a href="admin.php" class="nav-btn">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="result_std.php" class="nav-btn">
                    <span>🔙</span>
                    <span>Back To List</span>
                </a>
            </nav>
        </div>
    </header>
    <div class="all_student fix">
        <div class="student-info">
            <?php echo "Name: " . htmlspecialchars($name) . "<br>Student ID: " . htmlspecialchars($stid); ?>
        </div>
        <div style="text-align:center; margin-bottom: 1rem;">
            <!-- <a href="view_cgpa.php?vr=<?php echo urlencode($stid); ?>&vn=<?php echo urlencode($name); ?>"> -->
                <button class="editbtn">View CGPA & Completed Course</button>
            </a>
        </div>
        <form action="" method="post" style="width:23%;margin:0 auto;padding-bottom:5px;">
            <select name="seme" required>
                <option value="1st">1st semester</option>
                <option value="2nd">2nd semester</option>
                <option value="3rd">3rd semester</option>
            </select>
            <input type="submit" value="View Result" />
        </form>
        <?php
        //select semester
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $semester = $_POST['seme'];
            $i=0;
            $ch = 0;
            $gp = 0;

            // Get results for this student and semester
            $get_result = mysqli_query($con, "SELECT * FROM result WHERE ID='$stid' AND semester='$semester'");
            if($get_result && mysqli_num_rows($get_result) > 0){
        ?>
            <p style="text-align:center;background:#ddd;color:#01C3AA;padding:5px;width:84%;margin:0 auto"><?php echo $semester; ?> Semester Result</p>
            <table class="tab_two">
                <th>Subject</th>
                <th>Marks</th>
                <th>Grade</th>
                <th>Credit hr.</th>
                <th>Status</th>
        <?php
                while($rows = mysqli_fetch_assoc($get_result)){
                    $i++;
                    $ch = $ch + credit_hour($rows['sub']);
        ?>
                <tr>
                    <td><?php echo htmlspecialchars($rows['sub']);?></td>
                    <td><?php echo htmlspecialchars($rows['marks']);?></td>
                    <td>
                        <?php 
                        $mark = $rows['marks'];
                        if($mark<40){echo "F";}
                        elseif($mark>=40 && $mark<50){echo "D";}
                        elseif($mark>=50 && $mark<60){echo "C";}
                        elseif($mark>=60 && $mark<70){echo "B";}
                        elseif($mark>=70 && $mark<80){echo "A";}
                        elseif($mark>=80 && $mark<=100){echo "A+";}
                        $gp = $gp + (credit_hour($rows['sub']) * grade_point($rows['marks']));
                        ?>
                    </td>
                    <td><?php echo credit_hour($rows['sub']); ?></td>
                    <td>
                        <?php
                        $stat = $rows['marks'];
                        if($stat<40){
                            echo "<span style='background:red;padding:3px 11px;color:#fff;'>Fail</span>";
                        }else{
                            echo "<span style='background:green;padding:3px 6px;color:#fff;'>Pass</span>";
                        }
                        ?>
                    </td>
                </tr>
        <?php } ?>
                <tr>
                    <td colspan="2">Total CGPA : </td>
                    <td colspan="2">
                        <?php
                        $sg = $gp/$ch;
                        echo "<span style='color:green;padding:3px 6px;font-size:20px'>" . round($sg,2) . "</span>";
                        ?>
                    </td>
                    <td>
                        <?php
                        if($sg>=3.75){
                            echo "<span style='background:purple;padding:3px 6px;color:#fff;'>Excellent";
                        }elseif($sg>=3.0 && $sg<3.75){
                            echo "<span style='background:green;padding:3px 6px;color:#fff;'>Good";
                        }elseif($sg>=2.5 && $sg<3.0){
                            echo "<span style='background:gray;padding:3px 6px;color:#fff;'>Average";
                        }else{
                            echo "<span style='background:red;padding:3px 6px;color:#fff;'>Probation";
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <!-- <p style="float:left; text-align:right;margin:20px 0;width:49%">
                <a href="st_result_update.php?ar=<?php echo urlencode($stid);?>&seme=<?php echo urlencode($semester);?>&vn=<?php echo urlencode($name);?>">
                    <button class="editbtn">Edit Result</button>
                </a>
            </p> -->
        <?php
            } else {
                echo  "<p class='msg error'>Nothing Found</p>";
            }
        }
        ?>
        <!-- <p style="float:right;text-align:left;margin:20px 0;width:49%">
            <a href="st_result.php"><button class="editbtn">Back to list</button></a>
        </p> -->
    </div>
</body>
</html>