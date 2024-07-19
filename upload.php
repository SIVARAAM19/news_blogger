<?php
session_start();
include("php/config.php");

if (!isset($_SESSION['id'])) {
    header('Location: truescope.php');
    exit;
}

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die('Failed to create directory: ' . $uploadDir);
        }
    }

    $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
    $fileName = basename($_FILES['profile_pic']['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileType, $allowedExtensions)) {
        echo "Invalid file type.";
        exit;
    }

    if ($_FILES['profile_pic']['size'] > 500000) {
        echo "File size exceeds limit.";
        exit;
    }

    $newFilePath = $uploadDir . $user_id . '-' . time() . '.' . $fileType;

    if (move_uploaded_file($fileTmpPath, $newFilePath)) {
        // Insert into profile_pic and update users table
        $con->begin_transaction();
        try {
            $stmt = $con->prepare("INSERT INTO profile_pic (user_id, file_path) VALUES (?, ?)");
            $stmt->bind_param('is', $user_id, $newFilePath);
            $stmt->execute();
            $profilePicId = $stmt->insert_id;
            $stmt->close();

            $stmt = $con->prepare("UPDATE users SET profile_pic_id = ? WHERE id = ?");
            $stmt->bind_param('ii', $profilePicId, $user_id);
            $stmt->execute();
            $stmt->close();

            $con->commit();
            echo "Profile picture uploaded successfully.";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Failed to update database.";
        }
    } else {
        echo "File upload failed.";
    }
} else {
    echo "No file uploaded.";
}
?>
