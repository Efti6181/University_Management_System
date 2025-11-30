<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Student Admission System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #1e293b;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
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

        /* Main Container */
        .main-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Page Title */
        .page-title {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
        }

        .page-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .page-title p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Form Card */
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 2.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }

        .form-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #64748b;
            font-size: 1rem;
        }

        .form-body {
            padding: 2.5rem;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .required {
            color: #dc2626;
        }

        .form-input,
        .form-select {
            padding: 1rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
            color: #1f2937;
            font-family: inherit;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .form-input:hover,
        .form-select:hover {
            border-color: #d1d5db;
        }

        /* File Upload Styling */
        .file-upload-container {
            position: relative;
        }

        .file-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: #f9fafb;
            position: relative;
            overflow: hidden;
        }

        .file-upload-area:hover {
            border-color: #667eea;
            background: #f0f4ff;
            transform: translateY(-2px);
        }

        .file-upload-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 3rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .file-upload-text {
            color: #374151;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .file-upload-hint {
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Submit Section */
        .submit-section {
            border-top: 1px solid #e5e7eb;
            padding: 2.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1.25rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            font-family: inherit;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Info Cards */
        .info-section {
            margin-top: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .info-card-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .info-card h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .info-card p {
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .header-nav {
                justify-content: center;
                flex-wrap: wrap;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .main-container {
                padding: 0 1rem;
                margin: 1rem auto;
            }

            .form-body {
                padding: 2rem;
            }

            .submit-section {
                padding: 2rem;
            }

            .page-title h2 {
                font-size: 2rem;
            }

            .info-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .university-info h1 {
                font-size: 1.25rem;
            }

            .page-title h2 {
                font-size: 1.75rem;
            }

            .nav-btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .form-body {
                padding: 1.5rem;
            }

            .submit-section {
                padding: 1.5rem;
            }
        }

        /* Additional Professional Touches */
        .form-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .help-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
            font-style: italic;
        }

        /* Subtle animations */
        .form-input,
        .form-select,
        .file-upload-area {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-card {
            transition: transform 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-2px);
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

    <!-- Main Content -->
    <main class="main-container">
        <div class="page-title">
            <h2>Student Admission System</h2>
            <p>Complete the form below to register a new student in the university system</p>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <h3>Student Registration Form</h3>
                <p>Please fill in all required information accurately</p>
            </div>

<form class="form-body" action="stdsign.php" method="post" enctype="multipart/form-data">
    <div class="form-section-title">Personal Information</div>
    <div class="form-grid">
        <!-- Student ID -->
        <div class="form-group">
            <label class="form-label" for="sid">
                Student ID <span class="required">*</span>
            </label>
            <input 
                type="text" 
                id="sid" 
                name="sid" 
                class="form-input"
                placeholder="Student ID.." 
                required
            >
        </div>
        <!-- Name -->
        <div class="form-group">
            <label class="form-label" for="name">
                Name <span class="required">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                class="form-input"
                placeholder="Student Full Name.." 
                required
            >
        </div>
        <!-- Batch -->
        <div class="form-group">
            <label class="form-label" for="batch">
                Batch <span class="required">*</span>
            </label>
            <input 
                type="text" 
                id="batch" 
                name="batch" 
                class="form-input"
                placeholder="Student Batch.." 
                required
            >
        </div>
        <!-- Department -->
        <div class="form-group">
            <label class="form-label" for="dept">
                Department Name <span class="required">*</span>
            </label>
            <select id="dep" name="dept" class="form-select" required>
                    <option value="Computer Science and Engineering" selected>Computer Science and Engineering</option>
                    <option value="Electrical and Electronic Engineering">Electrical and Electronic Engineering</option>
                    <option value="English Language & Literature">English Language & Literature</option>
                    <option value="Business Administration">Business Administration</option>
                    <option value="Law">Law</option>
                    <option value="Economics">Economics</option>
                    <option value="Fashion Design and Technology">Fashion Design and Technology</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Architecture">Architecture</option>
                    <option value="Sociology and Sustainable Development">Sociology and Sustainable Development</option>
                    <option value="Public Health">Public Health</option>
                </select>
        </div>
        <!-- Mobile -->
        <div class="form-group">
            <label class="form-label" for="mobile">
                Mobile No. <span class="required">*</span>
            </label>
            <input 
                type="text" 
                id="mobile" 
                name="mobile" 
                class="form-input"
                placeholder="Student Mobile No.." 
                required
            >
        </div>
        <!-- Password -->
        <div class="form-group">
            <label class="form-label" for="pass">
                Password <span class="required">*</span>
            </label>
            <input 
                type="password" 
                id="pass" 
                name="pass" 
                class="form-input"
                placeholder="Set password = 123456" 
                required
            >
        </div>
        <!-- Picture -->
        <div class="form-group full-width">
            <label class="form-label" for="image">
                Picture <span class="required">*</span>
            </label>
            <input 
                type="file" 
                id="image" 
                name="pic" 
                class="form-input"
                required
            >
        </div>
    </div>
    <div class="submit-section">
        <input type="submit"  class="submit-btn"  value="Admit Student" name="submit">
        <!-- <span>✓</span> -->
        <!-- <span>Admit Student</span> -->
    </div>
</form>

        <!-- Information Cards -->
        <div class="info-section">
            <div class="info-card">
                <div class="info-card-icon">📋</div>
                <h4>Required Documents</h4>
                <p>Ensure all information is accurate and complete before submission</p>
            </div>
            <div class="info-card">
                <div class="info-card-icon">🔒</div>
                <h4>Secure Process</h4>
                <p>All student data is encrypted and stored securely in our system</p>
            </div>
            <div class="info-card">
                <div class="info-card-icon">⚡</div>
                <h4>Instant Processing</h4>
                <p>Student records are processed immediately upon form submission</p>
            </div>
        </div>
    </main>
</body>
</html>

<?php
include("../connection.php");
if(isset($_POST['submit']))
{
	$name=$_POST['name'];
	$batch=$_POST['batch'];
	$dept=$_POST['dept'];
	$mobile=$_POST['mobile'];
	$id=$_POST['sid'];
	$pass=$_POST['pass'];
	//customer id generation
	$num=rand(10,100);
	$cus_id="c-".$num;
	
	//image upload code
	$ext= explode(".",$_FILES['pic']['name']);
    $c=count($ext);
    $ext=$ext[$c-1];
    $date=date("D:M:Y");
    $time=date("h:i:s");
    $image_name=md5($date.$time.$cus_id);
    $image=$image_name.".".$ext;
	 
	
	
	$query="insert into student values('$id','$name',$mobile,'$dept',$batch,'$image','$pass')";
	if(mysqli_query($con,$query))
	{
		echo "Successfully inserted!";
		if($image !=null){
	                move_uploaded_file($_FILES['pic']['tmp_name'],"Simage/$image");
                    }
    }
	else
	{
		echo "error!".mysqli_error($con);
	}
}
?>