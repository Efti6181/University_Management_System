<?php
session_start();
include("../connection.php");

// Show errors for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_GET['tid'])) {
    $teacher_tid = $_GET['tid'];

    // Delete teacher from database
    $delete = mysqli_query($con, "DELETE FROM teacher WHERE tid='$teacher_tid'");

    if ($delete && mysqli_affected_rows($con) > 0) {
        header('Location: view_fac.php?res=1');
        exit();
    }
} 
?>