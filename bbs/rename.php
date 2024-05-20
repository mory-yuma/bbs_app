<?php 
require("./dbconnect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['rename'])) {
    $new_name = trim($_POST['rename']);
    if ($new_name !== '') {
        $statement = $db->prepare('UPDATE members SET name = ? WHERE id = ?');  
        $statement->execute([$new_name, $_SESSION['user_id']]);

        $_SESSION['user_name'] = $new_name;
        header('Location: index.php'); 
        exit();
    } else {
        echo "新しい名前を入力してください。";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ユーザー名変更</title>
</head>
<body>
    <div class="content">
        <form action="" method="POST" class="form">
            <h2>ユーザー名変更フォーム</h2>
            <hr>
            <div><p>現在のユーザー名：<?php echo $_SESSION['user_name'];?></p></div>
            <label for="rename">変更後のユーザー名</label>
            <input type="text" name="rename" placeholder="新しいユーザー名を入力してください">
            <button type="submit" class="btn">変更する</button>
        </form>
        <a href="index.php">戻る</a>
    </div>
</body>
</html>