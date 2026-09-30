<?php
include 'db_connection.php';

$uploadDir = 'uploads/';
$updatePairs = [];
$uploadedCount = 0;


for ($i = 1; $i <= 20; $i++) {
    if (isset($_FILES["file_$i"])) {
        $fileName = $_FILES["file_$i"]['name'];
        $tempPath = $_FILES["file_$i"]['tmp_name'];
        $targetPath = $uploadDir . time() . "_" . $fileName; // Unique filename

        if (move_uploaded_file($tempPath, $targetPath)) {
            $updatePairs[] = "attach$i = '" . mysqli_real_escape_string($conn, $targetPath) . "'";
            $updatePairs[] = "attach{$i}_name = '" . mysqli_real_escape_string($conn, $fileName) . "'";
            $uploadedCount++;
        }
    }
}

if ($uploadedCount > 0) {
   
	$sql = "UPDATE msg SET ... WHERE id = x" . implode(', ', $updatePairs);
    
    
    if ($conn->query($sql)) {
        echo "Success: $uploadedCount files uploaded and stored.";
    } else {
        echo "Database Error: " . $conn->error;
    }
} else {
    echo "No files were uploaded.";
}


?>