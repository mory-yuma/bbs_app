<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=bbs-yt; charset=utf8', 'root', 'root');
}   catch (PDOException $e) {
    echo "データベース接続エラー　：".$e->getMessage();
}
?>
