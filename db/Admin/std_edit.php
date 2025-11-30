<?php
// filepath: c:\xampp\htdocs\db\Admin\view_std.php
session_start();
include("../connection.php");

$pageTitle = "Update Student Information";

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: std_edit.php");
    exit();
}

$st_id = mysqli_real_escape_string($con, $_GET['id']);

$query = "SELECT * FROM student WHERE ID = '$st_id'";
$result = mysqli_query($con, $query);

if(!$result || mysqli_num_rows($result) == 0) {
    header("Location: std_edit.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['Update'])){
    $st_name = mysqli_real_escape_string($con, $_POST['st_name']);
    $batch = mysqli_real_escape_string($con, $_POST['batch']);
    $mobileno = mysqli_real_escape_string($con, $_POST['mobileno']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $update_query = "UPDATE student SET name='$st_name', batch='$batch', mobileno='$mobileno', password='$password' WHERE ID='$st_id'";

    if(mysqli_query($con, $update_query)){
        $success_message = "Student information updated successfully!";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
    } else {
        $error_message = "Error updating student information: " . mysqli_error($con);
    }
}
?>

<style>
/* Added professional CSS styling similar to std_details.php */
.admin-container {
    max-width: 800px;
    margin: 20px auto;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    overflow: hidden;
}

.admin-header {
    background: rgba(255,255,255,0.1);
    padding: 20px;
    text-align: center;
    color: white;
    font-size: 24px;
    font-weight: bold;
}

.admin-content {
    background: white;
    padding: 30px;
}

.form-container {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.form-group label {
    width: 120px;
    font-weight: 600;
    color: #333;
    margin-right: 15px;
}

.form-group input {
    flex: 1;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-container {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .form-group {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .form-group label {
        width: 100%;
        margin-bottom: 8px;
    }
    
    .btn-container {
        flex-direction: column;
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

    <header class="header">
        <div class="header-content">
            <div class="university-logo">
                <div class="logo-icon">🎓</div>
                <div class="university-info">
                    <h1>Premier University Chittagong</h1>
                    <p>Edit Student Information Portal</p>
                </div>
            </div>
            <nav class="header-nav">
                <a href="admin.php" class="nav-btn">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="view_std.php" class="nav-btn">
                    <span>🔙</span>
                    <span>Back To List</span>
                </a>
            </nav>
        </div>
    </header>

<div class="admin-container">
    <!-- <div class="admin-header">
        Edit Student Information
    </div> -->
    
    <div class="admin-content">
        <?php if(isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label for="st_name">Name:</label>
                    <input type="text" id="st_name" name="st_name" value="<?php echo htmlspecialchars($row['name']);?>" required>
                </div>
                
                <div class="form-group">
                    <label for="batch">Batch:</label>
                    <input type="text" id="batch" name="batch" value="<?php echo htmlspecialchars($row['batch']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phoneno">Phone No:</label>
                    <input type="text" id="mobileno" name="mobileno" value="<?php echo htmlspecialchars($row['mobileno']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($row['password']); ?>" required>
                </div>
                
                <div class="btn-container">
                    <input type="submit" name="Update" value="Update Student" class="btn btn-primary">
                    <!-- <a href="view_std.php" class="btn btn-secondary">Back to Students List</a> -->
                </div>
            </form>
        </div>
    </div>
</div>
