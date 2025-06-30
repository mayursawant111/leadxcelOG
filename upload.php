<?php
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["resume"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Allow certain file formats
if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
    echo json_encode(['message' => 'Sorry, only PDF, DOC, & DOCX files are allowed.']);
    exit;
}

// Check if $uploadOk is set to 0 by an error
if (move_uploaded_file($_FILES["resume"]["tmp_name"], $targetFile)) {
    echo json_encode(['message' => 'The file has been uploaded.']);
} else {
    echo json_encode(['message' => 'Sorry, there was an error uploading your file.']);
}
?>
