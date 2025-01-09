<?php
session_start();
require_once('funcs.php');
loginCheck();

//１. POSTデータ取得
$name   = $_POST['name'];
$url    = $_POST['url'];
$comment = $_POST['comment'];

echo $name;

//２. DB接続する
$pdo = db_conn();

//３．データ登録SQL作成

// 1)SQL文を用意
$stmt = $pdo->prepare(
  'INSERT INTO
    gs_bm_table(
      id,name,url,comment,date
    )
  VALUES(
    NULL, :name, :url, :comment, now()
    )'
);

// 2)バインド変数を用意
//Integer（数値の場合 PDO::PARAM_INT)
//String（文字列の場合 PDO::PARAM_STR) 念には念を入れて書いている。もっとわかりにくい変数名のときに、文字列だよ、と示している。
// 悪意あるSQLを実行させないために、１クッション置いている
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);

// 3)実行する。実行した結果（エラーも）は$stmtが持っている。
$status = $stmt->execute();

// 4)データ登録処理後 成功した場合：true、失敗した場合：false
if ($status === false) {
  //SQL実行時にエラーがある場合 SQLエラー関数を呼び出す
  sql_error($stmt);
} else {
  // 5)index.phpにリダイレクト。リダイレクト関数を呼び出す
  redirect("index.php");
}
?>