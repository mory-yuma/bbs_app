<?php

try {
    $db = new PDO('mysql:host=localhost;dbname=bbs-yt; charset=utf8', 'root', 'root');
}   catch (PDOException $e) {
    echo "データベース接続エラー　：".$e->getMessage();
}

// define("DB_USERNAME", 'root');
// define("DB_PASWORD", 'root');
// define("DSN", 'mysql:host=localhost;dbname=bbs-yt; charset=utf8');

// function db_connect(){
//     $dbh = new PDO(DSN,DB_USERNAME,DB_PASSWORD);
//     return $dbh;
// }
?>
