<?php
include 'db_connection.php'; 


$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';


$base_filter = "(file_path != 'admin_admin' AND file_path != 'admin_admin_dir') OR file_path IS NULL";
$where_clause = " WHERE ($base_filter)";


if (!empty($search)) {
    $where_clause .= " AND (company_name LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%'
                        OR imo_number LIKE '%$search%' 
                        OR official_number LIKE '%$search%')";
}


switch ($sort) {
    case 'name_az':   $order_by = "company_name ASC"; break;
    case 'name_za':   $order_by = "company_name DESC"; break;
    case 'date':      $order_by = "created_at DESC"; break; 
    case 'email_az':  $order_by = "email ASC"; break;
    case 'email_za':  $order_by = "email DESC"; break;
    case 'vessel_az': $order_by = "username ASC"; break; 
    case 'vessel_za': $order_by = "username DESC"; break;
    
    default:          $order_by = "created_at DESC"; break;
}


$sql = "SELECT * FROM NIMDAT $where_clause ORDER BY $order_by LIMIT 10";
$result = $conn->query($sql);


if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
       
        $displayDate = !empty($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : 'N/A';
        
       
        $company = !empty($row['company_name']) ? htmlspecialchars($row['company_name']) : '';
        $username = htmlspecialchars($row['username']?? '');
        $email = htmlspecialchars($row['email']);

        echo "<tr class='clickable-row' data-value='{$row['id']}' onclick='handleRowClick(this)'>
                <td></td>
                <td><strong>$company</strong></td>
                <td class='text-muted'>$email</td>
                <td>$displayDate</td>
                <td>$username</td>
                <td>
                    <button class='delete-btn' onclick='event.stopPropagation(); if(confirm(\"Delete user?\")) { deleteRecord({$row['id']}, this); }'>
                        🗑️
                    </button>
                </td>
              </tr>";
    }
} else {
    
    echo "<tr><td colspan='6' style='text-align:center; padding:20px;'>No results found.</td></tr>";
}
?>