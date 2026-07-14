<?php
require 'db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cert_id = trim($_POST['certificate_id']);
    $student_name = trim($_POST['student_name']);
    $course = trim($_POST['course_completed']);
    $grad_date = trim($_POST['graduation_date']);
    
    // Check if both files were uploaded
    if (isset($_FILES['passport']) && $_FILES['passport']['error'] == UPLOAD_ERR_OK &&
        isset($_FILES['certificate']) && $_FILES['certificate']['error'] == UPLOAD_ERR_OK) {
        
        $maxSize = 5 * 1024 * 1024; // 5MB limit for both
        
        if ($_FILES['passport']['size'] > $maxSize || $_FILES['certificate']['size'] > $maxSize) {
             $message = "<span style='color:red;'>Error: Files must be under 5MB.</span>";
        } else {
             $pass_ext = strtolower(pathinfo($_FILES['passport']['name'], PATHINFO_EXTENSION));
             $cert_ext = strtolower(pathinfo($_FILES['certificate']['name'], PATHINFO_EXTENSION));
             
             $allowed_img = ['jpg', 'jpeg', 'png'];
             $allowed_cert = ['jpg', 'jpeg', 'png', 'pdf'];
             
             if(in_array($pass_ext, $allowed_img) && in_array($cert_ext, $allowed_cert)) {
                 $new_pass_name = uniqid('passport_', true) . '.' . $pass_ext;
                 $new_cert_name = uniqid('cert_', true) . '.' . $cert_ext;
                 
                 $pass_path = 'uploads/' . $new_pass_name;
                 $cert_path = 'uploads/' . $new_cert_name;
                 
                 if(move_uploaded_file($_FILES['passport']['tmp_name'], $pass_path) && 
                    move_uploaded_file($_FILES['certificate']['tmp_name'], $cert_path)) {
                     
                     $stmt = $pdo->prepare("INSERT INTO graduates (certificate_id, student_name, course_completed, graduation_date, passport_image, certificate_file) VALUES (?, ?, ?, ?, ?, ?)");
                     try {
                         $stmt->execute([$cert_id, $student_name, $course, $grad_date, $pass_path, $cert_path]);
                         $message = "<span style='color:green;'>Student and Certificate successfully added!</span>";
                     } catch(Exception $e) {
                         $message = "<span style='color:red;'>Error: Certificate ID might already exist.</span>";
                     }
                 } else {
                     $message = "<span style='color:red;'>Failed to save files to folder.</span>";
                 }
             } else {
                 $message = "<span style='color:red;'>Invalid format. Passport must be JPG/PNG. Certificate can be PDF/JPG/PNG.</span>";
             }
        }
    } else {
        $message = "<span style='color:red;'>Please upload both a passport and a certificate file.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Upload Graduate</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; padding: 40px; }
        .form-container { background: white; padding: 25px; border-radius: 8px; max-width: 400px; margin: auto; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        input { width: 90%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; width: 100%; border-radius: 4px; }
        button:hover { background-color: #218838; }
        .file-label { font-size: 14px; color: #555; margin-bottom: 5px; display: block; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Upload Graduate Record</h2>
        <?php if($message) echo "<p>$message</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="certificate_id" placeholder="Certificate ID (e.g. TS-2026-001)" required>
            <input type="text" name="student_name" placeholder="Full Student Name" required>
            <input type="text" name="course_completed" placeholder="Course Completed" required>
            <input type="date" name="graduation_date" required>
            
            <label class="file-label">Passport Photo (JPG/PNG):</label>
            <input type="file" name="passport" accept="image/png, image/jpeg" required>
            
            <label class="file-label">Certificate Document (PDF/JPG/PNG):</label>
            <input type="file" name="certificate" accept="image/png, image/jpeg, application/pdf" required>
            
            <button type="submit">Upload to Database</button>
        </form>
    </div>
</body>
</html>