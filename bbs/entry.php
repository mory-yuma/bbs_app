<?php 
require("./dbconnect.php");
session_start();
if (!empty($_POST)) {
    if ($_POST['email'] === "") {
        $error['email'] = "blank";
    }
    if ($_POST['password'] === "") {
        $error['password'] = "blank";
    }
    if (!isset($error)) {
        $member = $db->prepare('SELECT COUNT(*) as cnt FROM members WHERE email=?');
        $member->execute(array(
            $_POST['email']
        ));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
    }
    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location: check.php');
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>アカウント作成</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content">
        <form action="" method="POST" class="form">
            <h1>アカウント作成</h1>
            <p>当サービスをご利用するために、次のフォームに必要事項をご記入ください。</p>
            <br>
            <div class="control">
                <input id="name" type="text" name="name" placeholder="ユーザー名">
            </div>
            <div class="control">
                <input id="email" type="email" name="email" placeholder="メールアドレス">
                <?php if (!empty($error["email"]) && $error['email'] === 'blank'): ?>
                    <p class="error">＊メールアドレスを入力してください</p>
                <?php elseif (!empty($error["email"]) && $error['email'] === 'duplicate'): ?>
                    <p class="error">＊このメールアドレスはすでに登録済みです</p>
                <?php endif ?>
            </div>
            <div class="control">
                <input id="password" type="password" name="password" placeholder="パスワード">
                <?php if (!empty($error["password"]) && $error['password'] === 'blank'): ?>
                    <p class="error">＊パスワードを入力してください</p>
                <?php endif ?>
            </div>
            <div class="control">
                <button type="submit" class="btn">確認する</button>
            </div>
        </form>
        <p><a href="login.php">ログインはこちら</a></p>
    </div>
</body>
</html>