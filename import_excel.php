<?php
session_start();
include 'Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();

// Handle Excel upload
if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['excel_file'];
    $fileName = $uploadedFile['name'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Check if file is Excel or CSV
    $allowedTypes = ['xlsx', 'xls', 'csv'];
    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['import_error'] = "Invalid file type. Please upload Excel (.xlsx, .xls) or CSV files only.";
        header('Location: item_list.php');
        exit;
    }
    
    // Check file size (max 5MB)
    if ($uploadedFile['size'] > 5 * 1024 * 1024) {
        $_SESSION['import_error'] = "File size too large. Please upload files smaller than 5MB.";
        header('Location: item_list.php');
        exit;
    }
    
    // Create upload directory if it doesn't exist
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate unique filename
    $uniqueFileName = 'import_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.' . $fileType;
    $uploadPath = $uploadDir . $uniqueFileName;
    
    // Move uploaded file
    if (move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {
        $_SESSION['import_success'] = "File uploaded successfully! Processing...";
        $_SESSION['imported_file'] = $uploadPath;
        $_SESSION['imported_filename'] = $fileName;
        
        // Try to process the Excel file
        try {
            // Check if PHPSpreadsheet is available
            if (file_exists('vendor/autoload.php')) {
                require 'vendor/autoload.php';
                
                if (class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
                    $ioFactoryClass = 'PhpOffice\PhpSpreadsheet\IOFactory';
                    $spreadsheet = $ioFactoryClass::load($uploadPath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $excelData = $sheet->toArray();
                    
                    if (!empty($excelData) && count($excelData) > 1) {
                        $_SESSION['excel_data'] = $excelData;
                        $_SESSION['import_success'] = "File uploaded and processed successfully! " . (count($excelData) - 1) . " items found.";
                    } else {
                        $_SESSION['import_error'] = "No data found in the Excel file.";
                    }
                } else {
                    $_SESSION['import_error'] = "PHPSpreadsheet library not found. Please install it first.";
                }
            } else {
                $_SESSION['import_error'] = "PHPSpreadsheet library not found. Please install it first.";
            }
        } catch (Exception $e) {
            $_SESSION['import_error'] = "Error processing Excel file: " . $e->getMessage();
        }
    } else {
        $_SESSION['import_error'] = "Failed to upload file. Please try again.";
    }
} else {
    $_SESSION['import_error'] = "No file uploaded or upload error occurred.";
}

// Redirect back to item list
header('Location: item_list.php');
exit;
?>
