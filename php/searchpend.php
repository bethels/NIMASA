<?php
require '../php/check_session.php'; 
include 'db_connection.php'; 

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';


$user_email = $_SESSION['user_email'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where_clause = " WHERE email = '$user_email' AND (status = 9 OR status = 0 OR status = 0.4)";
if (!empty($search)) {
    $where_clause .= " AND (dat1 LIKE '%$search%' OR dat3 LIKE '%$search%'
                        OR imo_number LIKE '%$search%' 
                        OR official_number LIKE '%$search%')";
}


switch ($sort) {
     case 'name_az':   $order_by = "dat1 ASC"; break;
    case 'name_za':   $order_by = "dat1 DESC"; break;
    case 'date':      $order_by = "date DESC"; break;
    case 'vessel_az': $order_by = "dat3 ASC"; break;
    case 'vessel_za': $order_by = "dat3 DESC"; break;
    
   
    case 'stat_pending':  $order_by = "CASE WHEN status = 0 THEN 0 ELSE 1 END ASC, date DESC"; break;
    case 'stat_approved': $order_by = "CASE WHEN status = 1 THEN 0 ELSE 1 END ASC, date DESC"; break;
	 case 'stat_verifying': $order_by = "CASE WHEN status = 9 THEN 0 ELSE 1 END ASC, date DESC"; break;
    case 'stat_rejected': $order_by = "CASE WHEN status = 3 THEN 0 ELSE 1 END ASC, date DESC"; break;
    
    default:        $order_by = "CASE WHEN status = 9 THEN 1 WHEN status = 0 THEN 2 WHEN status = 1 THEN 4 ELSE 3 END ASC, date DESC";
}

$sql = "SELECT * FROM cabotage $where_clause ORDER BY $order_by LIMIT 10";
$result = $conn->query($sql);


if ($result && $result->num_rows > 0) {
     while($row = $result->fetch_assoc()) {
        
        $status = (float)$row['status'];
        if (abs($status - 0.4) < 0.0001) { $c = 'pending'; $t = 'Receipt Submission';  }
		elseif($status == 9) { $c = 'verifying'; $t = 'Verifying'; }
        elseif($status == 0) { $c = 'pending'; $t = 'Processing'; }
        elseif($status == 1) { $c = 'approved'; $t = 'Completed'; }
        else { $c = 'rejected'; $t = 'Rejected'; }
        
       
        $last_mod = $row['last_modified'] ?? null;
        $expiry_display = "N/A";
        $bg_color = "";

        if ($last_mod && $last_mod != '0000-00-00 00:00:00'&&$status==1) {
            $expiry_time = strtotime('+1 year', strtotime($last_mod));
            $warning_threshold = strtotime('-3 months', $expiry_time);
            $today = time();

            if ($today >= $expiry_time) {
                $bg_color = 'background-color: #ff4d4d; color: white;'; // Red
            } elseif ($today >= $warning_threshold) {
                $bg_color = 'background-color: #ffff00; color: black;'; // Yellow
            }
            $expiry_display = date('M d, Y', $expiry_time);
        }
$display_text = ($status == 1) ? $expiry_display : "None";
        
		
        echo "<tr class='clickable-row' data-value='{$row['id']}' onclick='handleRowClick(this)'>
                <td></td>
                <td><strong>" . htmlspecialchars($row['dat1'] ?? '') . "</strong></td>
                <td class='text-muted'>" . htmlspecialchars($row['email'] ?? '') . "</td>
                <td>" . date('M d, Y', strtotime($row['date'] ?? 'now')) . "</td>
                <td><span class='status-badge $c'>$t</span></td>
                <td>" . htmlspecialchars($row['dat3'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['imo_number'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['official_number'] ?? '') . "</td>
                <td style='{$bg_color} font-weight: bold;'>$display_text</td>
                <td>
                    <button class='delete-btn' onclick='event.stopPropagation(); deleteRecord({$row['id']}, this)'>🗑️</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='10' style='text-align:center; padding:20px;'>No results found.</td></tr>";
}
?>