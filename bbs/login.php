<?php 
require("./dbconnect.php");
session_start();

$error = array();
if (!empty($_POST)) {
    if ($_POST['name'] === "") {
        $error['name'] = "blank";
    }
    if ($_POST['email'] === "") {
        $error['email'] = "blank";
    }
    if ($_POST['password'] === "") {
        $error['password'] = "blank";
    }

    if (empty($error)) {
        $statement = $db->prepare('SELECT * FROM members WHERE name=? AND email=? ');
        $statement->execute([$_POST['name'], $_POST['email']]);
        $member = $statement->fetch();

        if ($member && password_verify($_POST['password'], $member['password'])) {
            $_SESSION['user_id'] = $member['id'];
            $_SESSION['user_name'] = $member['name'];
            header('Location: index.php'); 
            exit();
        } else {
            $error['login'] = "failed";
            echo "ログインできませんでした。";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ログイン画面</title>
</head>
<body>
    <div class="content">
        <form action="" method="POST" class="form">
            <h1>アカウント情報入力フォーム</h1>
            <p>アカウントの情報を入力してください</p>
            <br>

            <div class="control">
                <input id="name" type="text" name="name" placeholder="ユーザー名">
                <?php if (!empty($error["name"]) && $error['name'] === 'blank'): ?>
                    <p class="error">＊名前を入力してください</p>
                <?php endif ?>
            </div>

            <div class="control">
                <input id="email" type="email" name="email" placeholder="メールアドレス">
                <?php if (!empty($error["email"]) && $error['email'] === 'blank'): ?>
                    <p class="error">＊メールアドレスを入力してください</p>
                <?php endif ?>
            </div>

            <div class="control">
                <input id="password" type="password" name="password" placeholder="パスワード">
                <?php if (!empty($error["password"]) && $error['password'] === 'blank'): ?>
                    <p class="error">＊パスワードを入力してください</p>
                <?php endif ?>
            </div>

            <div class="control">
                <button type="submit" class="btn">ログイン</button>
            </div>
        </form>
        <p><a href="entry.php">新規登録はこちら</a></p>
</body>
</html>