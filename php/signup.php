<?php
session_start(); 
header('Content-Type: application/json');

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. BASIC ACCOUNT INFO
    $email      = $_POST['email'] ?? '';
    $uname      = $_POST['username'] ?? '';
    $passRaw    = $_POST['password'] ?? '';
    $coname     = $_POST['company_name'] ?? '';
    $co_type    = $_POST['business_type'] ?? '';
    $co_nat     = $_POST['nationality'] ?? '';
    $rc_num     = $_POST['rc_number'] ?? '';
    $tin        = $_POST['tin'] ?? '';
    $year_inc   = $_POST['year_incorporated'] ?? '';

    // 2. CONTACT INFO
    $cfname     = $_POST['contact_firstname'] ?? '';
    $clname     = $_POST['contact_lastname'] ?? '';
    $cphone     = $_POST['contact_phone'] ?? '';
    $coadd      = $_POST['company_address'] ?? '';
    $coadd2     = $_POST['company_address2'] ?? '';
    $city       = $_POST['city'] ?? '';
    $state      = $_POST['state'] ?? '';
    $p_code     = $_POST['postal_code'] ?? '';
    $country    = $_POST['country'] ?? '';

    // 3. DIRECTOR 1 INFO (Unique naming)
    $d1_n1      = $_POST['director_name1'] ?? '';
    $d1_n2      = $_POST['director_name2'] ?? '';
    $d1_no      = $_POST['director_no'] ?? '';
    $d1_nat     = $_POST['director_nationality'] ?? '';
    $d1_add     = $_POST['director_address'] ?? '';

    // 4. LOOP TO COLLECT DIRECTORS 2-8
    
    $extra_directors = [];
    for ($i = 1; $i <= 7; $i++) {
        $extra_directors[] = $_POST["director{$i}_name1"] ?? '';
        $extra_directors[] = $_POST["director{$i}_name2"] ?? '';
        $extra_directors[] = $_POST["director{$i}_no"] ?? '';
        $extra_directors[] = $_POST["director{$i}_nationality"] ?? '';
        $extra_directors[] = $_POST["director{$i}_address"] ?? '';
      
    }

    // 5. HASH PASSWORD
    $hashedPass = password_hash($passRaw, PASSWORD_DEFAULT);

    // 6. CHECK IF EMAIL EXISTS
    $checkStmt = $conn->prepare("SELECT email FROM nimdat WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered!"]);
        exit;
    }

    // 7. PREPARE THE MASSIVE SQL STATEMENT
    
    $sql = "INSERT INTO nimdat (
        email, username, password, company_name, business_type, nationality, rc_number, tin, year_incorporated,
        contact_firstname, contact_lastname, contact_phone, company_address, company_address2, city, state, postal_code, country,
        director_name1, director_name2, director_no, director_nationality, director_address,
        director1_name1, director1_name2, director1_no, director1_nationality, director1_address,
        director2_name1, director2_name2, director2_no, director2_nationality, director2_address,
        director3_name1, director3_name2, director3_no, director3_nationality, director3_address,
        director4_name1, director4_name2, director4_no, director4_nationality, director4_address,
        director5_name1, director5_name2, director5_no, director5_nationality, director5_address,
        director6_name1, director6_name2, director6_no, director6_nationality, director6_address,
        director7_name1, director7_name2, director7_no, director7_nationality, director7_address
    ) VALUES (" . str_repeat('?,', 57) . "?)"; 
    
    $sql = str_replace(",)", ")", $sql);

    $stmt = $conn->prepare($sql);

    // 8. BIND PARAMS (The "s" string must be exactly 58 characters long)
    $types = str_repeat('s', 58); 
    
    // Merge all variables into one flat array for call_user_func_array OR bind_param
    $params = [
        $email, $uname, $hashedPass, $coname, $co_type, $co_nat, $rc_num, $tin, $year_inc,
        $cfname, $clname, $cphone, $coadd, $coadd2, $city, $state, $p_code, $country,
        $d1_n1, $d1_n2, $d1_no, $d1_nat, $d1_add
    ];
    // Add the extra directors from the loop array
    $params = array_merge($params, $extra_directors);

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $_SESSION['temp_verify_email'] = $email;
        echo json_encode(["status" => "success", "message" => "Account Created Successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error: " . $conn->error]);
    }
    
    $stmt->close();
}
$conn->close();
?>