<?php
require __DIR__. '/__connect_db.php';
$page_name = 'edit_me';
$page_title = '編輯個人資料';


// 如果沒有登入, 轉到首頁
if(! isset($_SESSION['user'])){
    header('Location: ./');
    exit;
}


?>
<?php include __DIR__. '/__html_head.php' ?>
<?php include __DIR__. '/__navbar.php' ?>
    <style>
        span.red, small {
            color: red;
            font-weight: bold;
        }
    </style>
<div class="container">
    <div class="row">
        <div class="col-lg-6">

            <div id="info" class="alert" role="alert" style="display: none"></div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">編輯個人資料</h5>
                    <div class="card-text">

                    <form name="form1" method="post" onsubmit="return formCheck()">
                        <div class="form-group">
                            <label for="email">電子郵箱</label>
                            <input type="text" class="form-control" id="email" name="email" disabled
                                   value="<?= $_SESSION['user']['email'] ?>">
                            <small id="emailHelp" class="form-text"></small>
                        </div>
                        <div class="form-group">
                            <label for="password"><span class="red">*</span> 輸入密碼用以變更資料</label>
                            <input type="text" class="form-control" id="password" name="password">
                            <small id="passwordHelp" class="form-text"></small>
                        </div>
                        <div class="form-group">
                            <label for="nickname"><span class="red">*</span> 暱稱</label>
                            <input type="text" class="form-control" id="nickname" name="nickname"
                                   value="<?= $_SESSION['user']['nickname'] ?>">
                            <small id="nicknameHelp" class="form-text"></small>
                        </div>


                        <div class="form-group">
                            <label for="mobile">手機</label>
                            <input type="text" class="form-control" id="mobile" name="mobile"
                                   value="<?= $_SESSION['user']['mobile'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="address">地址</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value="<?= $_SESSION['user']['address'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="birthday">生日</label>
                            <input type="text" class="form-control" id="birthday" name="birthday"
                                   value="<?= $_SESSION['user']['birthday'] ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">修改</button>
                    </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
    <script>

        var fields = ['nickname', 'password'];
        var i, s;
        var info = $('#info');

        function formCheck(){
            info.hide();
            // 讓每一欄都恢復原來的狀態
            for(s in fields){
                cancelAlert(fields[s]);
            }

            var isPass = true;

            if(document.form1.nickname.value.length < 2){
                setAlert('nickname', '請輸入正確的暱稱');
                isPass = false;
            }

            if(document.form1.password.value.length < 6){
                setAlert('password', '密碼請輸入六個字以上');
                isPass = false;
            }

            if(isPass){

                $.post('edit_me_api.php', $(document.form1).serialize(), function(data){
                    var alertType = 'alert-danger';

                    info.removeClass('alert-danger');
                    info.removeClass('alert-success');

                    if(data.success){
                        alertType = 'alert-success';


                        setTimeout(function(){
                            location.reload(); // 重新載入
                        }, 1000);
                    } else {
                        alertType = 'alert-danger';
                    }
                    info.addClass(alertType);
                    if(data.info){
                        info.html( data.info );
                        info.slideDown();
                    }
                }, 'json');

            }

            return false;
        }

        // 設定警示
        function setAlert(fieldName, msg){
            $('#'+fieldName).css('border', '1px solid red');
            $('#'+fieldName+'Help').text(msg);
        }

        // 取消警示
        function cancelAlert(fieldName){
            $('#'+fieldName).css('border', '1px solid #cccccc');
            $('#'+fieldName+'Help').text('');
        }
    </script>
<?php include __DIR__. '/__html_foot.php' ?>