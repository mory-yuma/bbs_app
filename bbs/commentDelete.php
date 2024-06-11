<?php
require('./dbconnect.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "ログインしてください";
    exit;
}
$user_id = $_SESSION['user_id'];
$comment_id = $_POST['delete_comment_id'];
$sql = "SELECT user_id FROM `bbs-table` WHERE id = :comment_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['user_id'] == $user_id) {
        $delete_sql = "DELETE FROM `bbs-table` WHERE id = :comment_id";
        $delete_stmt = $db->prepare($delete_sql);
        $delete_stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $delete_stmt->execute();
        header('location: index.php');
        exit;
    }
}
?>
