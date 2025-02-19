<?php
if (isset($_POST['submit'])) {
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'fantsuam_foundation';
    
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    foreach ($_FILES['files']['name'] as $key => $val) {
        $fileName = basename($_FILES['files']['name'][$key]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $targetFilePath)) {
            if (strtolower($fileType) == 'zip') {
                $zip = new ZipArchive;
                if ($zip->open($targetFilePath) === TRUE) {
                    $zip->extractTo($targetDir);
                    $zip->close();
                    unlink($targetFilePath); // Remove the uploaded zip file
                    echo "ZIP file extracted successfully.<br>";
                } else {
                    echo "Failed to extract ZIP file.<br>";
                }
            } elseif (strtolower($fileType) == 'pdf') {
                $insert = $db->query("INSERT INTO staff_record-2 (file_name) VALUES ('$fileName')");
                if ($insert) {
                    echo "$fileName uploaded successfully.<br>";
                } else {
                    echo "Database insert failed for $fileName.<br>";
                }
            } else {
                echo "$fileName is not a PDF or ZIP file. Skipped.<br>";
            }
        } else {
            echo "Failed to upload $fileName.<br>";
        }
    }
}
?> 
