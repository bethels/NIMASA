<?php
header('Content-Type: application/json');

require 'database.php';

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if (empty($email)) {
        echo json_encode(["status" => "error", "message" => "No email provided"]);
        exit;
    }

    // --- PART 1: Physical File Deletion ---
    // Look for file paths in the cabotage table first
    $fileQuery = "SELECT * FROM cabotage WHERE email = ?";
    $stmtFile = $conn->prepare($fileQuery);
    $stmtFile->bind_param("s", $email);
    $stmtFile->execute();
    $result = $stmtFile->get_result();

    if ($row = $result->fetch_assoc()) {
        // Loop through dat1 to dat28
        for ($i = 1; $i <= 28; $i++) {
            $colName = "dat" . $i;
            if (!empty($row[$colName])) {
                // Assuming your files are stored in a path like 'uploads/filename.pdf'
                // and this PHP script is inside a 'php/' folder.
                $filePath = $row[$colName]; 

                if (file_exists($filePath)) {
                    unlink($filePath); // This deletes the actual file from your XAMPP folder
                }
            }
        }
    }
    $stmtFile->close();

    // --- PART 2: Database Record Deletion ---
    // Using a Transaction to ensure all tables are cleaned or none are.
    $conn->begin_transaction();

    try {
        // 1. Delete from nimdat (Account Info)
        $d1 = $conn->prepare("DELETE FROM nimdat WHERE email = ?");
        $d1->bind_param("s", $email);
        $d1->execute();

        // 2. Delete from cabotage (Application Data)
        $d2 = $conn->prepare("DELETE FROM cabotage WHERE email = ?");
        $d2->bind_param("s", $email);
        $d2->execute();

        // 3. Delete from msg (Messaging - uses 'mail' column)
        $d3 = $conn->prepare("DELETE FROM msg WHERE mail = ?");
        $d3->bind_param("s", $email);
        $d3->execute();

        $conn->commit();
        echo json_encode(["status" => "success"]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Database wipe failed: " . $e->getMessage()]);
    }
}

$conn->close();
?>