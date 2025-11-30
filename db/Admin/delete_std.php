
<?php
session_start();
include("../connection.php");

// Get student ID from request
if (isset($_GET['id'])) {
    $st_ID = $_GET['id'];

    // Delete student from database
    $delete = mysqli_query($con, "DELETE FROM student WHERE ID='$st_ID'");

    if ($delete) {
        header('Location: view_std.php?res=1');
        exit();
    }
} 
?>