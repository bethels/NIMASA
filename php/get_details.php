<?php

include 'db_connect.php'; 

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
   
    $query = "SELECT * FROM cabotage WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
       
        ?>
        <h3>Application Details</h3>
        <hr>
        <p><strong>Company:</strong> <?php echo $row['dat1']; ?></p>
        <p><strong>Vessel Name:</strong> <?php echo $row['dat3']; ?></p>
        <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
        <p><strong>Date Submitted:</strong> <?php echo date('M d, Y', strtotime($row['date'])); ?></p>
        
        <div style="display:none;" class="status-badge"><?php echo $row['status']; ?></div>

        <div class="status-group">
            <h4>Update Status</h4>
            <label><input type="radio" name="app_status" value="0" onchange="updateApplicationStatus(this.value)"> Incomplete (Processing)</label><br>
            <label><input type="radio" name="app_status" value="9" onchange="updateApplicationStatus(this.value)"> Verifying (Pending)</label><br>
            <label><input type="radio" name="app_status" value="1" onchange="updateApplicationStatus(this.value)"> Completed (Approved)</label><br>
            <label><input type="radio" name="app_status" value="3" onchange="updateApplicationStatus(this.value)"> Rejected</label>
        </div>
        <?php
    } else {
        echo "Record not found.";
    }
}
?>