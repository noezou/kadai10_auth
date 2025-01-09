<?php
session_start();
require_once('funcs.php'); // 関数を定義しているファイルを呼び出す
//1. POSTデータ取得
$name   = $_POST['name'];
$url  = $_POST['url'];
$comment = $_POST['comment'];
$id     = $_POST['id'];

//2. DB接続する
$pdo = db_conn();

//３．データ登録SQL作成
$sql = 'UPDATE gs_bm_table SET name=:name,url=:url,comment=:comment,
      date =sysdate() WHERE id = :id;';

$stmt = $pdo->prepare($sql);
// 数値の場合 PDO::PARAM_INT
// 文字の場合 PDO::PARAM_STR
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
// $stmt->bindValue(':date', $date, PDO::PARAM_STR);

$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status === false) {
  sql_error($stmt);
} else {
  redirect("select.php");
}

?>
