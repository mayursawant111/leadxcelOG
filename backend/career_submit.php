<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

require_once __DIR__ . '/database.php';

// Validate required fields
$required = ['fullName', 'phone', 'email', 'experience', 'position', 'noticePeriod', 'motivation'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required field: $field"]);
        exit;
    }
}

// File validation
if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== 0) {
    http_response_code(400);
    echo json_encode(["error" => "Please upload a valid resume file."]);
    exit;
}

// Resume file validation
$allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
$file = $_FILES['resume'];
$fileSizeMB = $file['size'] / (1024 * 1024);
if (!in_array($file['type'], $allowedTypes) || $fileSizeMB > 5) {
    http_response_code(400);
    echo json_encode(["error" => "Resume must be a PDF, DOC, or DOCX file under 5MB."]);
    exit;
}

// Save file
$uploadDir = '../uploads/resumes/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Format name: lowercase, dashes, no special chars
$sanitizedName = preg_replace('/[^a-z0-9]/i', '-', strtolower($_POST['fullName']));
$sanitizedName = preg_replace('/-+/', '-', $sanitizedName); // remove double dashes

// Get original file extension
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

// Format datetime
$datetime = date('Ymd_His');

// Final filename
$filename = $sanitizedName . '_' . $datetime . '.' . $ext;

$targetPath = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to upload resume."]);
    exit;
}

$resumeUrl = 'uploads/resumes/' . $filename;

// Get form data
$name = $_POST['fullName'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$currentCTC = $_POST['currentCTC'] ?? '';
$experience = $_POST['experience'];
$position = $_POST['position'];
$noticePeriod = $_POST['noticePeriod'];
$motivation = $_POST['motivation'];
$createdAt = date('Y-m-d H:i:s');

// Insert into database
$stmt = $conn->prepare("INSERT INTO resumes (name, phone, email, current_ctc, yr_of_exp, applied_position, notice_period, resume_url, joining_reason, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $name, $phone, $email, $currentCTC, $experience, $position, $noticePeriod, $resumeUrl, $motivation, $createdAt);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Thank you for your application! We'll review your profile and get back to you within 48 hours."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to save your application. MySQL error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
