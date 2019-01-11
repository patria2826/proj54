<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="./">小新的店</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item <?= ($page_name == 'products') ? 'active' : '' ?>">
                    <a class="nav-link" href="./products.php">products</a>
                </li>
                <li class="nav-item <?= $page_name == 'cart' ? 'active' : '' ?>">
                    <a class="nav-link" href="my_cart.php">購物車
                        <span class="badge badge-pill badge-info">0</span>
                    </a>
                </li>
                <ul class="navbar-nav">

                    <li class="nav-item <?= $page_name == 'tools' ? 'active' : '' ?>">
                        <a class="nav-link" href="tools.php">Tools</a>
                    </li>

                </ul>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['user']['nickname'] ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="edit_me.php">編輯個人資料</a>
                            <a class="dropdown-item" href="buy_history.php">訂單</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">修改密碼(自己做)</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">登出</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item <?= $page_name == 'login' ? 'active' : '' ?>">
                        <a class="nav-link" href="login.php">登入</a>
                    </li>
                    <li class="nav-item <?= $page_name == 'register' ? 'active' : '' ?>">
                        <a class="nav-link" href="register.php">註冊</a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>
<style>
    li.nav-item.active {
        background-color: #9fcdff;
    }
</style>
<script>
    var badge_pill = $('.badge-pill');

    function cart_count(obj) {
        var k, items = 0;
        for (k in obj) {
            items += obj[k];
        }
        badge_pill.text(items);
    }

    $.get('cart_add_sess.php', function (data) {
        cart_count(data);
    }, 'json');
</script>