<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "nimasa");

// Query to get unique users who have messaged or received messages from admin
// We exclude 'admin_admin' and 'admin_admin_dir' from the list
$sql = "SELECT DISTINCT email_user FROM (
            SELECT sender AS email_user FROM chat WHERE sender NOT IN ('admin_admin', 'admin_admin_dir')
            UNION
            SELECT receiver AS email_user FROM chat WHERE receiver NOT IN ('admin_admin', 'admin_admin_dir')
        ) AS combined_users ORDER BY email_user ASC";

$result = $conn->query($sql);
?>
<!--
<div id="contact-sidebar" style="width: 300px; background: #fff; border-right: 1px solid #ddd; height: 100vh; overflow-y: auto;">
    <div style="background: #075e54; color: white; padding: 15px; font-weight: bold; font-size: 18px;">
        Chats
    </div>
    -->
    <div id="contact-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="contact-item" onclick="handleUserClick('<?php echo $row['email_user']; ?>')">
                    <div class="avatar"><?php echo strtoupper(substr($row['email_user'], 0, 1)); ?></div>
                    <div class="contact-info">
                        <div class="contact-name"><?php echo $row['email_user']; ?></div>
                        <div class="contact-status">Click to view messages</div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="padding: 20px; text-align: center; color: #999;">No conversations found.</div>
        <?php endif; ?>
    </div>
</div>

<style>
    .contact-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-bottom: 1px solid #f2f2f2;
        cursor: pointer;
        transition: background 0.2s;
    }
    .contact-item:hover {
        background-color: #f5f5f5;
    }
    .avatar {
        width: 45px;
        height: 45px;
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 15px;
    }
    .contact-info {
        flex: 1;
        overflow: hidden;
    }
    .contact-name {
        font-weight: 600;
        font-size: 14px;
        color: #333;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .contact-status {
        font-size: 12px;
        color: #888;
    }
</style>



<script>

function handleUserClick(email) {
    // Alert as requested
    alert("Opening chat for: " + email);
    
    // In your real app, you would do this:
    // trueEmail = email; 
    // loadChat(email);
}

</script>