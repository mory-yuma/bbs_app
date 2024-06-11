<?php
session_start();
date_default_timezone_set("Asia/Tokyo");
$comment_array = array();
$pdo = null;
$stmt = null;

$error_messages = array();
// DB接続
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bbs-yt', "root", "root");
} catch (PDOException $e) {
    echo $e->errorInfo();
}
if (!empty($_POST["submitButton"])) {
    //画像をupload
    if (!empty($_FILES['image']['name'])) {
        $errors = array();
        // アップロードされたファイルの情報を取得
        //$_FILES['input_name']['fileの情報']
        $file_name = $_FILES['image']['name'];//uploadされたファイルのファイル名
        $file_size = $_FILES['image']['size'];//ファイルサイズ
        $file_tmp = $_FILES['image']['tmp_name'];//サーバーへ仮アップロードされたディレクトリとファイル名？
        $file_type = $_FILES['image']['type'];//ファイルの形式「png」「jpeg」等
        // アップロードされたファイルの拡張子を取得
        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));//ex.pngをupした場合、「png」を取得
        //explode - 分割し配列に変換  end - 最後の要素を取得  strtolower - 取得した文字を小文字に変換
        // 許可する拡張子を定義
        $extensions = array("jpeg", "jpg", "png");
        // 拡張子のチェック
        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "このファイル形式は許可されていません。jpeg, jpg, pngファイルのみアップロードできます。";
            $error_messages["invalidimageuploadtype"] = "このファイル形式は許可されていません。jpeg, jpg, pngファイルのみアップロードできます。";
        }
        // ファイルサイズのチェック（例：1MB以下）
        if ($file_size > 1048576) {
            $errors[] = 'ファイルサイズが大きすぎます。1MB以下のファイルのみアップロードできます。';
            $error_messages["invalidimageuploadtype"] = 'ファイルサイズが大きすぎます。1MB以下のファイルのみアップロードできます。';

        }
        // エラーがない場合、ファイルを指定されたディレクトリに保存
        if (empty($errors)) {
            $image_path = "uploads/" . $file_name;
            move_uploaded_file($file_tmp, $image_path);
        } else {
            // エラーがある場合、エラーメッセージを表示
            print_r($errors);
        }
    }
    //名前のチェック
    if (empty($_SESSION["user_name"])) {
        echo "ログインしてください";
        $error_messages["username"] = "ログインしてください";
    }
    //コメントのチェック
    if (empty($_POST["comment"])) {
        echo "コメントを入力してください。";
        $error_messages["comment"] = "コメントを入力してください";
    }
    if (empty($error_messages)) {
        $postDate = date("Y-m-d H:i:s");
        try {
            $stmt = $pdo->prepare("INSERT INTO `bbs-table` (`username`, `comment`, `postDate`, `image_path`, `user_id`) VALUES (:username, :comment, :postDate, :image_path, :user_id);");
            $stmt->bindParam(':username', $_SESSION['user_name'], PDO::PARAM_STR);
            $stmt->bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
            $stmt->bindParam(':postDate', $postDate, PDO::PARAM_STR);
            $stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            print_r($stmt->errorInfo());
        } catch (PDOException $e) {
            echo $e->errorInfo();
        }
    }
    if(!empty($_POST['commentDelete'])) {
        header('location: commentDelete.php');
    }
}
//DBからコメントデータを取得
$sql = "SELECT id, username, comment, postDate FROM `bbs-table`;";
$comment_array = $pdo->query($sql);
// 全データの件数を取得
$total = count($comment_array);
// ページサイズとページ番号を設定
$pageSize = 5;
$pageNum = isset($_GET['page']) ? $_GET['page'] : 1;//trueなら['page']の値をfalseなら1を返す。
// データの取得範囲を計算
$offset = ($pageNum - 1) * $pageSize;
// データの取得
$sql = "SELECT * FROM `bbs-table` LIMIT $offset, $pageSize";//データベースから取得するデータの範囲を制限する
$stmt = $pdo->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);//fetchAll - QLクエリの実行結果から全ての行を取得する PDO::FETCH_ASSOC - 連想配列として取得（idとか）
// ページリンクの生成
$prevPageLink = $pageNum > 1 ? '?page=' . ($pageNum - 1) : '';
$nextPageLink = $pageNum < ceil($total / $pageSize) ? '?page=' . ($pageNum + 1) : '';//ceil - 引数数値を小数点以下を切り上げて整数
    // ページ番号一覧の生成
    $pageList = range(1, ceil($total / $pageSize));
    $pdo = null;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PHP掲示板</title>
    <script>
    function confirmLogout() {
        // 確認ダイアログを表示し、ユーザーの選択結果に応じてログアウトを実行するかどうか判断する
        if (confirm("本当にログアウトしますか？")) {
            // ログアウトページにリダイレクトする
            window.location.href = "logout.php";
        }
    }
    </script>
</head>
<body>
<body>
    <header>
        <h1 class="title">PHP掲示板アプリ</h1>
        <?php if (!empty($_SESSION)):?>
            <div class="user_account"><p><?php echo $_SESSION['user_name'];?></p></div>
        <?php endif ?>    
        <?php if (!empty($_SESSION)):?>
            <div><a href="rename.php">名前を変更する</a></div>
        <?php endif ?>    
        <?php if(empty($_SESSION)): ?>
            <div class="login"><a href="login.php">ログイン</a></div>
        <?php endif ?>     
        <?php if (!empty($_SESSION)):?>
            <div class="logout"><a href="#" onclick="confirmLogout()">ログアウト</a></div>
        <?php endif ?>    
    </header>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach ($items as $comment) : ?>
                <article>
                    <div class="wrapper">
                        <div class="nameArea">
                            <span>名前：</span>
                            <p class="username"><?php echo $comment["username"];?></p>
                            <time>:<?php echo $comment["postDate"]; ?></time>
                        </div>
                        <p class="comment"><?php echo $comment["comment"];?></p>
                        <?php if (!empty($comment["image_path"])) : ?>
                            <img src="<?php echo $comment["image_path"]; ?>" alt="uploaded_image">
                        <?php endif; ?>
                        <?php if ($comment['user_id'] == $_SESSION['user_id']) : ?>
                            <form action="commentDelete.php"method="POST" onsubmit="return confirm('本当にこのコメントを削除しますか？');">
                                <input type="hidden" name="delete_comment_id" value="<?php echo $comment['id']; ?>">
                                <button type="submit" name="commentDelete">削除</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>    
        </section>
        <form class="formWrapper" method="POST" enctype="multipart/form-data">
            <div>
                <input type="submit" value="書き込む" name="submitButton" class="writingButton">
            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
            </div>
            <input type="file" name="image">
        </form>
        <div class="pagination">
            <?php if ($prevPageLink): ?>
                <a href="<?php echo $prevPageLink; ?>"><</a>
            <?php endif; ?>
            <?php foreach ($pageList as $page): ?>
                <?php if ($page == $pageNum): ?>
                    <a href="?page=<?php echo $page; ?>" class="active"><?php echo $page; ?></a>
                <?php else: ?>
                    <a href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($nextPageLink): ?>
                <a href="<?php echo $nextPageLink; ?>">></a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>