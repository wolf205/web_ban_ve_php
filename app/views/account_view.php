<?php
// app/views/account_view.php
require_once __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thông tin tài khoản - Beta Cinemas</title>

    <link rel="stylesheet" href="publics/css/variables.css" />
    <link rel="stylesheet" href="publics/css/reset.css" />
    <link rel="stylesheet" href="publics/css/container.css" />
    <link rel="stylesheet" href="publics/css/button.css" />
    <link rel="stylesheet" href="publics/css/card.css" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="publics/css/styles.css" />
    <link rel="stylesheet" href="publics/css/taikhoan1.css" />
  </head>
  <body>
    <main class="container account-section">
      <div class="account-layout">
        <!-- Top horizontal menu (moved from sidebar) -->
        <div class="account-topnav">
          <nav>
            <a href="index.php?controller=KhachHang&action=profile" class="sidebar-item active">THÔNG TIN TÀI KHOẢN</a>
            <a href="index.php?controller=KhachHang&action=hanhTrinh" class="sidebar-item">HÀNH TRÌNH ĐIỆN ẢNH</a>
          </nav>
        </div>

        <div class="account-content">
          <?php if (isset($_GET['status']) && $_GET['status'] === 'update_success'): ?>
            <div class="alert alert-success">
              Cập nhật thông tin thành công!
            </div>
          <?php endif; ?>

          <?php if (isset($error)): ?>
            <div class="alert alert-error">
              <?php echo htmlspecialchars($error); ?>
            </div>
          <?php endif; ?>

          <form action="index.php?controller=KhachHang&action=updateProfile" method="POST" enctype="multipart/form-data">
            <div class="account-layout-grid">
              <!-- Cột trái: Avatar -->
              <div class="account-left-column">
                <div class="avatar-section">
                  <div class="avatar-preview">
                    <?php if (!empty($khach_hang['avatar'])): ?>
                      <img src="<?php echo htmlspecialchars($khach_hang['avatar']); ?>" alt="Avatar">
                    <?php else: ?>
                      <i class="fas fa-user"></i>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="avatar-upload-container">
                  <div class="form-group">
                    <label for="avatar-upload" class="btn btn-outline">
                      <i class="fas fa-camera"></i>
                      Chọn ảnh
                    </label>
                    <input
                      type="file"
                      id="avatar-upload"
                      name="avatar-upload"
                      accept="image/*"
                      style="display: none"
                    />
                  </div>
                  <div class="avatar-upload-hint">JPG, PNG tối đa 5MB</div>
                </div>
              </div>

              <!-- Cột phải: Thông tin tài khoản (1 cột) -->
              <div class="account-right-column">
                <div class="form-grid">
                  <div class="form-group">
                    <label for="tai_khoan">Tài khoản</label>
                    <input
                      type="text"
                      id="tai_khoan"
                      name="tai_khoan"
                      value="<?php echo htmlspecialchars($khach_hang['tai_khoan'] ?? ''); ?>"
                      readonly
                      disabled
                    />
                  </div>

                  <div class="form-group">
                    <label for="ho_ten" class="label-required">Họ tên</label>
                    <input
                      type="text"
                      id="ho_ten"
                      name="ho_ten"
                      value="<?php echo htmlspecialchars($khach_hang['ho_ten'] ?? ''); ?>"
                      required
                    />
                  </div>

                  <div class="form-group">
                    <label for="email" class="label-required">Email</label>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      value="<?php echo htmlspecialchars($khach_hang['email'] ?? ''); ?>"
                      required
                    />
                  </div>

                  <div class="form-group">
                    <label for="sdt" class="label-required">Số điện thoại</label>
                    <input
                      type="tel"
                      id="sdt"
                      name="sdt"
                      value="<?php echo htmlspecialchars($khach_hang['SDT'] ?? ''); ?>"
                      required
                    />
                  </div>
                </div>

                <div class="form-actions">
                  <button type="submit" class="btn btn-primary">
                    Cập nhật
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>

    <script>
      // JavaScript để xử lý preview avatar và hiển thị tên file
      document.getElementById('avatar-upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            const avatarPreview = document.querySelector('.avatar-preview');
            avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Avatar preview" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
          }
          reader.readAsDataURL(file);
        }
      });

      // Click vào label để trigger file input
      document.querySelector('label[for="avatar-upload"]').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('avatar-upload').click();
      });
    </script>
  </body>
</html>
<?php
require_once __DIR__ . '/footer.php';
?>