<?php
require '../php/check_session.php'; 
header('Content-Type: application/json');

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['user_email'] ?? null;
    $oldTag = $_POST['tag'] ?? null;

    if (!$email || !$oldTag) {
        echo json_encode(["status" => "error", "message" => "Session or Tag missing"]);
        exit;
    }

    // 1. Get New Tag
    $resTag = $conn->query("SELECT MAX(tag) as max_tag FROM cabotage WHERE email = '$email'");
    $rowTag = $resTag->fetch_assoc();
    $newTag = ($rowTag['max_tag']) ? $rowTag['max_tag'] + 1 : 1;

    // 2. Build Column Lists
    $res = $conn->query("SHOW COLUMNS FROM cabotage");
    $columnsToCopy = [];
    $statusColumnsToReset = [];

    while ($row = $res->fetch_assoc()) {
        $name = $row['Field'];
        
        // Skip keys and system-managed columns
        if ($name == 'id' || $name == 'tag' || $name == 'date' || $name == 'last_modified') {
            continue;
        }

        // If it's a status column, we will set it to 0 manually
        if (stripos($name, 'status') !== false) {
            $statusColumnsToReset[] = $name;
        } else {
            // Otherwise, we copy the data (names, pay info, etc)
            $columnsToCopy[] = $name;
        }
    }

    // Prepare SQL strings
    $colNames = implode(", ", $columnsToCopy);
    $statusNames = implode(", ", $statusColumnsToReset);
    $statusValues = implode(", ", array_fill(0, count($statusColumnsToReset), "-10"));

    // 3. Execute Duplicate
    // We explicitly list every group to ensure counts match perfectly
    $sql = "INSERT INTO cabotage (tag, date, $statusNames, $colNames) 
            SELECT ?, NOW(), $statusValues, $colNames FROM cabotage 
            WHERE email = ? AND tag = ? LIMIT 1";

    try {
        $copyStmt = $conn->prepare($sql);
        $copyStmt->bind_param("isi", $newTag, $email, $oldTag);
        
        if ($copyStmt->execute()) {
            $_SESSION['user_tag'] = $newTag;
            echo json_encode(["status" => "success", "new_tag" => $newTag]);
        } else {
            // This captures SQL-specific errors (like constraint violations)
            echo json_encode(["status" => "error", "message" => "SQL Error: " . $copyStmt->error]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Exception: " . $e->getMessage()]);
    }
}
?>