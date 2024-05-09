<?php 
require("./dbconnect.php");
session_start();

//1.ログインフォームを作成
//2.入力された名前・アドレス・パスワードをデータベースからSELECT COUNTで取得
//3.if文 比較演算子 < 0 を使ってデータベースにあるかを確認
//4.

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
</head>
<body>
    作成中
    <div class="content">
        <form action="" method="POST">
            <h1>アカウント情報入力フォーム</h1>
            <p>アカウントの情報を入力してください</p>
            <br>

            <div class="control">
                <label for="name">ユーザー名</label>
                <input id="name" type="text" name="name">
            </div>

            <div class="control">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email">
            </div>

            <div class="control">
                <label for="password">パスワード</label>
                <input id="password" type="password" name="password">
            </div>

            <div class="control">
                <button type="submit" class="btn">ログイン</button>
            </div>
        </form>
</body>
</html>