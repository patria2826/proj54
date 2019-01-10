<?php
require __DIR__. '/__connect_db.php';

$result =[
    'success' => false,
    'code' => 400,
    'info' => '參數不足',
    'postData' => [],
];


if(isset($_POST['nickname']) and isset($_POST['password']) and isset($_SESSION['user'])){
    $result['postData'] = $_POST;

    // TODO: 檢查 nickname 的長度


    // 密碼編碼, 不要明碼
    $password = sha1(trim($_POST['password']));

    $sql = "UPDATE `members` SET `mobile`=?,`address`=?,`birthday`=?,`nickname`=? WHERE `id`=? AND `password`=?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $_POST['mobile'],
        $_POST['address'],
        $_POST['birthday'],
        $_POST['nickname'],
        $_SESSION['user']['id'],
        $password
    ]);

    // 影響的列數 (筆數)
    if($stmt->rowCount()==1){
        $result['success'] = true;
        $result['code'] = 200;
        $result['info'] = '資料更新完成';

        $_SESSION['user']['mobile'] = $_POST['mobile'];
        $_SESSION['user']['address'] = $_POST['address'];
        $_SESSION['user']['birthday'] = $_POST['birthday'];
        $_SESSION['user']['nickname'] = $_POST['nickname'];
    } else {
        $result['code'] = 410;
        $result['info'] = '資料沒有更新';
    }
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);









