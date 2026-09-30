<?php
require '../php/check_session.php'; 

// 1. Get the email from the session
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}
$user_email = $_SESSION['user_email'];

// 2. Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "nimasa";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. SEARCH & FILTER Logic (Restricted to Email AND Status 1)
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';


$where_clause = " WHERE email = '$user_email' AND (status = 0 OR status = 0.4 OR status = 9 )";

if (!empty($search)) {
    $where_clause .= " AND (dat1 LIKE '%$search%' OR dat3 LIKE '%$search%')";
}

// 4. PAGINATION MATH & TOTAL COUNT
$limit = 10; 
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Total count now automatically respects the 'status = 1' rule
$count_query = $conn->query("SELECT COUNT(*) as total FROM cabotage $where_clause");
$total_data_count = 0;
if ($count_query) {
    $count_row = $count_query->fetch_assoc();
    $total_data_count = $count_row['total'];
}
$total_pages = ceil($total_data_count / $limit);

// 5. SORTING Logic
$sort_type = isset($_GET['sort']) ? $_GET['sort'] : 'default';

switch ($sort_type) {
    case 'name_az':   $order_by = "dat1 ASC"; break;
    case 'name_za':   $order_by = "dat1 DESC"; break;
    case 'date':      $order_by = "`date` DESC"; break; 
    case 'vessel_az': $order_by = "dat3 ASC"; break;
    case 'vessel_za': $order_by = "dat3 DESC"; break;
    default:
        // Since all statuses are now '1', we just sort by date
        $order_by = "`date` DESC";
        break;
}

// 6. FINAL DATA FETCH
$sql = "SELECT * FROM cabotage $where_clause ORDER BY $order_by LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>



<!DOCTYPE html>
<head>

<title>Applications</title>
<link rel="stylesheet" href="../css/accountlist.css">
<meta name="viewport" content="width=device-width" initial-scale="1.0">
<link rel="icon" href="../images/logo.png" type="image/x-icon">
</head>

<html>
<body>
<script src="../JS/pendapp.js"></script>
<div class="topper">

<center class="jaba"> 
<div class="circleimg1">
<a href="../others/adminpanel.php"><img src="../images/logo.png" class="middle"></a>
</div>
<span class="BoldFont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nigerian Maritime Administration and Safety Agency</span>

</center>

</div>



<div class="portal2">





<div class="account" id="accounts">










<div class="admin-container">
    <div class="table-controls">
        <h2 class="title">Application(s) In Progress</h2>
		  <span class="text-muted">[Yellow(Within 3 months to expiration) Red(Expired) ]</span>
		
        
		
		<div class="actions-right">
    <div class="dropdown" style="position: relative; display: inline-block;">
        <button class="btn-sort" onclick="toggleSortMenu()">⇅ Sort-by</button>
        <div id="sortMenu" class="dropdown-content">
    <a href="?sort=default&search=<?php echo urlencode($search); ?>">Default Priority</a>
    <hr>
    <a href="?sort=name_az&search=<?php echo urlencode($search); ?>">Company Name (A-Z)</a>
    <a href="?sort=name_za&search=<?php echo urlencode($search); ?>">Company Name (Z-A)</a>
    <a href="?sort=vessel_az&search=<?php echo urlencode($search); ?>">Vessel Name (A-Z)</a>
    <a href="?sort=vessel_za&search=<?php echo urlencode($search); ?>">Vessel Name (Z-A)</a>
  
    <hr>
    <a href="?sort=date&search=<?php echo urlencode($search); ?>">Date (Newest)</a>
    <a href="?sort=stat_pending&search=<?php echo urlencode($search); ?>">Status (Processing First)</a>
    <a href="?sort=stat_approved&search=<?php echo urlencode($search); ?>">Status (Approved First)</a>
	<a href="?sort=stat_verifying&search=<?php echo urlencode($search); ?>">Status (Verifying First)</a>
    <a href="?sort=stat_rejected&search=<?php echo urlencode($search); ?>">Status (Rejected First)</a>
</div>
    </div>

  
    <input type="text" id="searchInput" 
           placeholder="Search Submissions..." 
           oninput="autoSearch()" 
           autocomplete="off">

</div>



    </div>

    <div class="table-wrapper">
        <table id="userTable">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Vessel</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
				<?php $result->data_seek(0); ?>
                    <?php while($row = $result->fetch_assoc()): 
                       
						  $status = (float)$row['status'];

						if (abs($status - 0.4) < 0.0001) { $c = 'pending'; $t = 'Receipt Submission';  }
                        elseif($status == 9) { $c = 'verifying'; $t = 'Verifying'; }
                        elseif($status == 0) { $c = 'pending'; $t = 'Processing'; }
                        elseif($status == 1) { $c = 'approved'; $t = 'Completed'; }
                        else { $c = 'rejected'; $t = 'Rejected'; }
                    ?>
                   <tr  
    class="clickable-row" 
    data-value="<?php echo $row['id']; ?>" 
    onclick="handleRowClick(this)">
    
    <td></td>
    <td><strong><?php echo !empty($row['dat1']) ? $row['dat1'] : 'Unnamed Company'; ?></strong></td>
    <td class="text-muted"><?php echo $row['email']; ?></td>
    <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
    <td><span class="status-badge <?php echo $c; ?>"><?php echo $t; ?></span></td>
    <td><?php echo $row['dat3']; ?></td>
    <td>
        <button class="delete-btn" onclick="event.stopPropagation(); if(confirm('Are you sure you want to delete this submission?')) { deleteRecord(<?php echo $row['id']; ?>, this); }">
		🗑️</button>
    </td>
</tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; padding:20px;">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span>Showing <?php echo ($total_data_count > 0) ? $offset + 1 : 0; ?>-<?php echo min($offset + $limit, $total_data_count); ?> of <?php echo $total_data_count; ?> entries</span>
        
        <div class="page-btns">
            <?php if($page > 1): ?>
                <a href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>">Prev</a>
            <?php endif; ?>

            <a href="?page=1&search=<?php echo $search; ?>" class="<?php echo ($page == 1) ? 'active' : ''; ?>">1</a>

            <?php if($page > 1 && $page < $total_pages): ?>
                <a href="?page=<?php echo $page; ?>" class="active"><?php echo $page; ?></a>
            <?php endif; ?>

            <?php if($page + 1 < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>"><?php echo $page + 1; ?></a>
            <?php endif; ?>

            <?php if($page + 2 < $total_pages): ?>
                <a href="?page=<?php echo $page + 2; ?>"><?php echo $page + 2; ?></a>
            <?php endif; ?>

            <?php if($page + 3 < $total_pages): ?> <span>...</span> <?php endif; ?>
            
            <?php if($total_pages > 1): ?>
                <a href="?page=<?php echo $total_pages; ?>" class="<?php echo ($page == $total_pages) ? 'active' : ''; ?>"><?php echo $total_pages; ?></a>
            <?php endif; ?>

            <?php if($page < $total_pages): ?>
                <a href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>
</div>






</div>




<div class="accountDET" id="accountDETS">

<p id="accountz" class="fonter"></p><br/>








<br/><br/>



<center><button id="Editable" class="lister" onclick="listEdit()">Edit</button></center>
<br/>
<center><button class="back" onclick="listClose()">CLOSE</button></center>
<br/><br/>
</div>







<br/>
&nbsp;



</div>




<div class="bottom">
<center class="BoldFont2">
© NIMASA|Nigerian Maritime Administration and Safety Agency
</center>
</div>



</body>
</html>