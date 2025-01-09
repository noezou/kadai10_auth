<?php
session_start();
require_once('funcs.php');
// loginCheck();

//1. POSTデータ取得
$id = $_GET['id'];  //今回はidだけど、nameだったらnameで受ける

// DBに接続する
$pdo = db_conn();

//2. データ登録SQL作成
$sql = 'SELECT * FROM gs_bm_table WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT); //stmt という箱の中から関数を取ってくる
$status = $stmt->execute(); //実行！ 成功したらtrue、失敗したらfalse

//3. データ表示
$result = '';
if ($status === false) {
  sql_error($stmt);
} else {
    $result = $stmt->fetch();
}

// 1行しかない場合fetchで
//var_dump($result); //確認テスト実施（普通は確認取れたら削除しておく）
// $json = json_encode($values, JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>データ詳細</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
</head>

<body>
  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header"><a class="navbar-brand" href="select.php">データ更新</a></div>
      </div>
    </nav>
  </header>

  <!-- method, action, 各inputのnameを確認してください。  -->
  <form method="POST" action="update.php">
    <div class="jumbotron">
      <fieldset>
        <legend>ブックマークデータ編集</legend>
        <label>書籍名：<input type="text" name="name" value="<?= $result['name'] ?>"></label><br>
        <label>ＵＲＬ：<input type="text" name="url" value="<?= $result['url'] ?>"></label><br>
        <label>コメント：<textarea name="comment" rows="4" cols="40"><?= $result['comment'] ?></textarea></label><br> <!-- textareaは終了タグがあるのでvalue属性は使えない -->
        <input type="hidden" name="id" value="<?= $result['id'] ?>"><!-- idは見せたくないのでhiddenにして隠す -->
        <input type="submit" value="送信">
      </fieldset>
    </div>
  </form>
</body>

</html>