<?php
require 'db.php';
header('Content-Type: application/json');

if (isset($_GET['cert_id'])) {
    $cert_id = trim($_GET['cert_id']);
    
    // Updated query to include certificate_file
    $stmt = $pdo->prepare("SELECT student_name, course_completed, graduation_date, passport_image, certificate_file FROM graduates WHERE certificate_id = ? LIMIT 1");
    $stmt->execute([$cert_id]);
    
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        echo json_encode(["status" => "success", "data" => $student]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid Certificate ID. No record found."]);
    }
}
?>