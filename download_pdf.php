<?php
if (isset($_POST['download_files'])) {
    $zip = new ZipArchive();
    $zipFileName = 'uploaded_documents.zip';

    if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
        exit("Cannot open <$zipFileName>\n");
    }

    $uploadDir = 'uploads/';

    // Collect files from the uploads directory
    $files = scandir($uploadDir);

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $filePath = $uploadDir . $file;
            if (is_file($filePath)) {
                $zip->addFile($filePath, $file);
            }
        }
    }

    $zip->close();

    // Force download the zip file
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($zipFileName));
    readfile($zipFileName);

    // Clean up the temporary zip file
    unlink($zipFileName);
    exit;
}
?>
