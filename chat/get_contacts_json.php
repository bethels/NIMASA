<?php
// get_contacts_json.php
header('Content-Type: application/json');

require_once '../php/db_connect.php'; 

try {
    // 2. This SQL finds unique users and counts unread messages
    // It filters out the admin's own ID so we only see the users
    $sql = "SELECT 
                chat_user, 
                MAX(send_date) as last_msg, 
                SUM(is_unread) as unread_count
            FROM (
                /* Get all senders who aren't admins */
                SELECT sender AS chat_user, send_date, (CASE WHEN replied = 0 THEN 1 ELSE 0 END) AS is_unread
                FROM chat
                WHERE sender NOT IN ('admin_admin', 'admin_admin_dir')
                
                UNION ALL
                
                /* Get all receivers who aren't admins */
                SELECT receiver AS chat_user, send_date, 0 AS is_unread
                FROM chat
                WHERE receiver NOT IN ('admin_admin', 'admin_admin_dir')
            ) AS combined
            GROUP BY chat_user
            ORDER BY last_msg DESC";

    $stmt = $pdo->query($sql);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Return the clean JSON
    echo json_encode($contacts);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
exit;