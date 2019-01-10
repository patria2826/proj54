<?php
require __DIR__. '/__connect_db.php';

$result =[
    'success' => false,
    'code' => 400,
    'info' => '參數不足',
    'postData' => [],
];


if(isset($_POST['email']) and isset($_POST['password'])){
    $result['postData'] = $_POST;


    // 去掉頭尾空白, 然後轉小寫
    $email = strtolower(trim($_POST['email']));
    // 密碼編碼, 不要明碼
    $password = sha1(trim($_POST['password']));

    $sql = "SELECT `id`, `email`, `mobile`, `address`, `birthday`, `nickname` FROM `members` WHERE `email`=? AND `password`=?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $email,
        $password,
    ]);

    // 影響的列數 (筆數)
    if($stmt->rowCount()==1){
        $result['success'] = true;
        $result['code'] = 200;
        $result['info'] = '登入成完成';

        $_SESSION['user'] = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $result['code'] = 410;
        $result['info'] = 'Email 或密碼錯誤';
    }
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);









