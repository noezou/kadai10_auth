<?php
// 0. SESSION開始！！
session_start();

// 1. ログインチェック処理！
// 以下、セッションID持ってたら、ok
// 持ってなければ、閲覧できない処理にする。

// 関数化して、他ページにも流用する
// if (!isset($_SESSION{'chk_ssid'}) || $_SESSION['chk_ssid'] != session_id()) {
//     // ログインを経由していない場合
//     exit('LOGIN ERROR!');
// };
// session_regenerate_id(true);
// $_SESSION['chk_ssid']= session_id();
require_once('funcs.php');
loginCheck();

// 1.DB接続する
$pdo = db_conn();

// 2.データ取得SQL作成 登録済みのものを持ってくるので攻撃気にしないで良い
$stmt = $pdo->prepare("SELECT * FROM gs_bm_table;");
$status = $stmt->execute();

// 3.データ表示
$value = '';
if ($status === false) {
  sql_error($stmt);
} else {
    // while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     $value .= '<p>';
    //     $value .= '<a href="detail.php?id=' . $r["id"] . '">';
    //     $value .= h($r['id']) . " " . h($r['name']) . " " . h($r['url']);
    //     $value .= '</a>';
    //     $value .= "　";

    //     if($_SESSION['admin_flg'] === 1){
    //     $value .= '<a class="btn btn-danger" href="delete.php?id=' . $r['id'] . '">';
    //     $value .= '[<i class="glyphicon glyphicon-remove"></i>削除]';
    //     $value .= '</a>';
    //     }

    //     $value .= '</p>';
    //     // echo $value;
    // }
$values = $stmt->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($values, JSON_UNESCAPED_UNICODE);

}

// 全データ取得する場合は、fetchAllを使用する
// $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
// $json = json_encode($values, JSON_UNESCAPED_UNICODE);

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ブックマーク（DB呼び出し、HTML表示）</title>
  <link rel="stylesheet" href="css/range.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }

    td {
      border: 1px solid brown;
    }
  </style>
</head>

<body id="main">
  <!-- Head[Start] -->
  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php">データ登録</a>
        </div>
      </div>
    </nav>
  </header>
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <div>
    <!-- <div class="container jumbotron"><?= $value ?></div> -->

        <table>
          <?php foreach ($values as $value) { ?>
            <tr>
              <td><?= h($value["id"]) ?></td>
              <td><?= h($value["name"]) ?></td>
              <td><?= h($value["url"]) ?></td>
              <td><?= h($value["comment"]) ?></td>
              <td><?= $value["date"] ?></td>
              <td><a href="detail.php?id=<?= h($value['id']) ?>">更新</a></td>?>
          <?php if($_SESSION['admin_flg'] === 1){ ?>
              <td><a href="delete.php?id=<?= h($value['id']) ?>">削除</a></td>
          <?php } ?>
            </tr>
          <?php } ?>
        </table>

  </div>
  <br>
  <h1>入力ページに戻る</h1>
  <div><a href="index.php">index.phpファイル</a>に戻ります</div>
  <!-- Main[End] -->
</body>

</html>