<?php
// app/views/register_view.php
require_once __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký tài khoản</title>

    <link rel="stylesheet" href="publics/css/variables.css" />
    <link rel="stylesheet" href="publics/css/reset.css" />
    <link rel="stylesheet" href="publics/css/container.css" />
    <link rel="stylesheet" href="publics/css/button.css" />
    <link rel="stylesheet" href="publics/css/taikhoan.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="publics/css/styles.css" />
  </head>
  <body>
    
    <main class="container login-section">
      <div class="login-card">
        <div class="login-tabs">
          <a href="index.php?controller=KhachHang&action=index" class="tab-item">Đăng nhập</a>
          <a href="index.php?controller=KhachHang&action=register" class="tab-item active">Đăng ký</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form class="login-form" action="index.php?controller=KhachHang&action=register" method="POST">
          <div class="form-group">
            <label for="ho_ten">Họ tên</label>
            <div class="input-with-icon">
              <input
                type="text"
                id="ho_ten"
                name="ho_ten"
                placeholder="Họ tên"
                value="<?php echo isset($_POST['ho_ten']) ? htmlspecialchars($_POST['ho_ten']) : ''; ?>"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
              <input
                type="email"
                id="email"
                name="email"
                placeholder="Email"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="sdt">Số điện thoại</label>
            <div class="input-with-icon">
              <input
                type="tel"
                id="sdt"
                name="sdt"
                placeholder="Số điện thoại"
                value="<?php echo isset($_POST['sdt']) ? htmlspecialchars($_POST['sdt']) : ''; ?>"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="tai_khoan">Tài khoản</label>
            <div class="input-with-icon">
              <input
                type="text"
                id="tai_khoan"
                name="tai_khoan"
                placeholder="Tài khoản đăng nhập"
                value="<?php echo isset($_POST['tai_khoan']) ? htmlspecialchars($_POST['tai_khoan']) : ''; ?>"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="mat_khau">Mật khẩu</label>
            <div class="input-with-icon">
              <input
                type="password"
                id="mat_khau"
                name="mat_khau"
                placeholder="Mật khẩu"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="xac_nhan_mat_khau">Xác nhận lại mật khẩu</label>
            <div class="input-with-icon">
              <input
                type="password"
                id="xac_nhan_mat_khau"
                name="xac_nhan_mat_khau"
                placeholder="Xác nhận lại mật khẩu"
                required
              />
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-login">
            Đăng ký
          </button>
        </form>
      </div>
    </main>

    <?php require_once __DIR__ . '/footer.php'; ?>
  </body>
</html>