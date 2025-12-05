<?php
// app/views/login_view.php
require_once __DIR__ . '/../chung/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập tài khoản</title>

    <link rel="stylesheet" href="publics/css/variables.css" />
    <link rel="stylesheet" href="publics/css/reset.css" />
    <link rel="stylesheet" href="publics/css/container.css" />
    <link rel="stylesheet" href="publics/css/button.css" />
    <link rel="stylesheet" href="publics/css/taikhoan1.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="publics/css/styles.css" />
</head>
<body>
    <main class="container login-section">
        <div class="login-card">
            <div class="login-tabs">
                <a href="index.php?controller=KhachHang&action=index" class="tab-item active">Đăng nhập</a>
                <a href="index.php?controller=KhachHang&action=register" class="tab-item">Đăng ký</a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'register_success'): ?>
                <div class="alert alert-success">
                    Đăng ký thành công! Vui lòng đăng nhập.
                </div>
            <?php endif; ?>

            <form class="login-form" action="index.php?controller=KhachHang&action=index" method="POST">
                <div class="form-group form-group-username">
                    <label for="tai_khoan">Tài khoản</label>
                    <div class="input-with-icon">
                        <input
                            type="text"
                            id="tai_khoan"
                            name="tai_khoan"
                            placeholder="Nhập tài khoản của bạn"
                            value="<?php echo isset($_POST['tai_khoan']) ? htmlspecialchars($_POST['tai_khoan']) : ''; ?>"
                            required
                        />
                    </div>
                </div>

                <div class="form-group form-group-password">
                    <label for="mat_khau">Mật khẩu</label>
                    <div class="input-with-icon">
                        <input
                            type="password"
                            id="mat_khau"
                            name="mat_khau"
                            placeholder="Nhập mật khẩu của bạn"
                            required
                        />
                    </div>
                </div>

                <div class="form-options">
                    <a href="#" class="forgot-password">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    Đăng nhập tài khoản
                </button>
            </form>
        </div>
    </main>
</body>
</html>
<?php
require_once __DIR__ . '/../chung/footer.php';
?>