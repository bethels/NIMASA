<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json');
error_reporting(0); 
require '../php/check_session.php'; 

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_SESSION['user_email'] ?? null;
    $tag = (int)($_POST['tag'] ?? 0);
    $uploadFolder = "../uploads/";

    if (!$mail) {
        echo json_encode(["status1" => "error", "message" => "Session expired"]);
        exit;
    }
    
    // Tag Generation Logic
    if ($tag <= 0) {
        $newTag = 1;
        while (true) {
            $cStmt = $conn->prepare("SELECT tag FROM cabotage WHERE tag = ?");
            $cStmt->bind_param("i", $newTag);
            $cStmt->execute();
            if ($cStmt->get_result()->num_rows == 0) {
                $tag = $newTag; $cStmt->close(); break;
            }
            $newTag++; $cStmt->close();
        }
    }
    
    $_SESSION['tag'] = $tag;

    // Fetch existing data
    $currentData = [];
    $checkStmt = $conn->prepare("SELECT * FROM cabotage WHERE email = ? AND tag = ?");
    $checkStmt->bind_param("si", $mail, $tag);
    $checkStmt->execute();
    $res = $checkStmt->get_result();
    $exists = $res->num_rows > 0;
    if ($exists) { $currentData = $res->fetch_assoc(); }
    $checkStmt->close();

    $final_values = [];
    // 1. Handle dat1 - dat5
    for ($i = 1; $i <= 5; $i++) {
        $final_values["dat$i"] = $_POST["dat$i"] ?? ($currentData["dat$i"] ?? "");
    }

    // 2. Handle New Text Fields (IMO and Official Number)
    $imo_number = $_POST['imono'] ?? ($currentData['imo_number'] ?? "");
    $official_number = $_POST['vofno'] ?? ($currentData['official_number'] ?? "");

    // 3. Handle dat6 - dat18 (Original Files)
    $paths = []; $names = []; $stats = [];
    for ($i = 6; $i <= 18; $i++) {
        $updateFlag = $_POST["update_flag_$i"] ?? 0;
        if ($updateFlag == 99 && isset($_FILES["dat$i"])) {
            $uniqueName = time() . "_dat$i" . "_" . basename($_FILES["dat$i"]["name"]);
            $target = $uploadFolder . $uniqueName;
            if (move_uploaded_file($_FILES["dat$i"]["tmp_name"], $target)) {
                $paths[$i] = $target;
                $names[$i] = $_POST["dat{$i}_name"] ?? "";
                $stats[$i] = 0;
            }
        } else {
            $paths[$i] = $currentData["dat$i"] ?? "";
            $names[$i] = $currentData["dat{$i}_name"] ?? "";
            $stats[$i] = (int)($currentData["dat{$i}_status"] ?? 0);
        }
    }

    // 4. Handle Special Columns (Tax, Manning, Labour Files)
    $specCols = ["tax_clearance", "manning_liscense", "maritime_labour"];
    $specPaths = []; $specNames = []; $specStats = [];

    foreach ($specCols as $col) {
        $updateFlag = $_POST["update_flag_$col"] ?? 0;
        if ($updateFlag == 99 && isset($_FILES[$col])) {
            $uniqueName = time() . "_" . $col . "_" . basename($_FILES[$col]["name"]);
            $target = $uploadFolder . $uniqueName;
            if (move_uploaded_file($_FILES[$col]["tmp_name"], $target)) {
                $specPaths[$col] = $target;
                $specNames[$col] = $_POST["{$col}_name"] ?? "";
                $specStats[$col] = 0;
            }
        } else {
            $specPaths[$col] = $currentData[$col] ?? "";
            $specNames[$col] = $currentData["{$col}_name"] ?? "";
            $specStats[$col] = (int)($currentData["{$col}_status"] ?? 0);
        }
    }

    // 5. Construct Parameters for Bind
    $sql_params = [];
    // Strings
    for($i=1; $i<=5; $i++) $sql_params[] = $final_values["dat$i"];
    $sql_params[] = $imo_number;
    $sql_params[] = $official_number;
    
    for($i=6; $i<=18; $i++) $sql_params[] = $paths[$i];
    for($i=6; $i<=18; $i++) $sql_params[] = $names[$i];
    
    foreach($specCols as $col) $sql_params[] = $specPaths[$col];
    foreach($specCols as $col) $sql_params[] = $specNames[$col];

    // Integers (Statuses)
    for($i=6; $i<=18; $i++) $sql_params[] = $stats[$i];
    foreach($specCols as $col) $sql_params[] = $specStats[$col];

    if ($exists) {
        $sql = "UPDATE cabotage SET 
                dat1=?, dat2=?, dat3=?, dat4=?, dat5=?, 
                imo_number=?, official_number=?,
                dat6=?, dat7=?, dat8=?, dat9=?, dat10=?, dat11=?, dat12=?, dat13=?, dat14=?, dat15=?, dat16=?, dat17=?, dat18=?,
                dat6_name=?, dat7_name=?, dat8_name=?, dat9_name=?, dat10_name=?, dat11_name=?, dat12_name=?, dat13_name=?, dat14_name=?, dat15_name=?, dat16_name=?, dat17_name=?, dat18_name=?,
                tax_clearance=?, manning_liscense=?, maritime_labour=?,
                tax_clearance_name=?, manning_liscense_name=?, maritime_labour_name=?,
                dat6_status=?, dat7_status=?, dat8_status=?, dat9_status=?, dat10_status=?, dat11_status=?, dat12_status=?, dat13_status=?, dat14_status=?, dat15_status=?, dat16_status=?, dat17_status=?, dat18_status=?,
                tax_clearance_status=?, manning_liscense_status=?, maritime_labour_status=?,
                status = -10, type = 3
                WHERE email = ? AND tag = ?";
        
        $sql_params[] = $mail;
        $sql_params[] = $tag;
        // Types: 5(dat) + 2(imo/vof) + 13(paths) + 13(names) + 3(specPaths) + 3(specNames) = 39s
        // 13(stats) + 3(specStats) = 16i ... then "si" for where
        $types = str_repeat("s", 39) . str_repeat("i", 16) . "si";
    } else {
        $sql = "INSERT INTO cabotage (
                dat1, dat2, dat3, dat4, dat5, imo_number, official_number,
                dat6, dat7, dat8, dat9, dat10, dat11, dat12, dat13, dat14, dat15, dat16, dat17, dat18, 
                dat6_name, dat7_name, dat8_name, dat9_name, dat10_name, dat11_name, dat12_name, dat13_name, dat14_name, dat15_name, dat16_name, dat17_name, dat18_name, 
                tax_clearance, manning_liscense, maritime_labour,
                tax_clearance_name, manning_liscense_name, maritime_labour_name,
                dat6_status, dat7_status, dat8_status, dat9_status, dat10_status, dat11_status, dat12_status, dat13_status, dat14_status, dat15_status, dat16_status, dat17_status, dat18_status,
                tax_clearance_status, manning_liscense_status, maritime_labour_status,
                email, tag, status, status1, type
                ) VALUES (" . str_repeat("?,", 59) . "?)";
        
        $sql_params[] = $mail;
        $sql_params[] = $tag;
        $sql_params[] = -10;
        $sql_params[] = "pending";
        $sql_params[] = 3;
        $types = str_repeat("s", 39) . str_repeat("i", 16) . "siiss";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$sql_params);

    if ($stmt->execute()) {
        echo json_encode(["status1" => "success", "message" => "Database Updated", "newTag" => $tag]);
    } else {
        echo json_encode(["status1" => "error", "message" => $stmt->error]);
    }
}
$conn->close();
?>