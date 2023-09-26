<?php

/**
 *   Đây là trang Login dùng để hiển thị form đăng nhập,
 *   Bất kì trang nào trong admin nếu không được xác thực
 *   hợp lệ đều bị đưa về trang này!
 */

$admin_public_dir = base_url() . '/public/admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login admin</title>

    <!-- CSS CDN Plugin files -->
    <link href="<?php echo $admin_public_dir; ?>/icon/font-awesome-6.4.2-pro-main/css/all.css" rel="stylesheet" />

    <!-- CSS CUSTOM -->
    <link href="<?php echo $admin_public_dir; ?>/css/style-login.css" rel="stylesheet" />
</head>

<body>
    <section class="login-section">
        <div class="container">

            <form class="form-login" action="<?php echo base_url(route_to('admin.dangnhap')); ?>" method="POST">
                <h2>Đăng nhập</h2>
                <div class="form-group">
                    <input type="text" class="user-name" name="username" placeholder="Tên đăng nhâp.." />
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Mật khẩu.." />
                    <i class="show-pass-icon fa-solid fa-eye-slash" onclick="toggleShowPassword();"></i>
                </div>
                <!-- nút -->
                <div class="form-submit">
                    <input type="submit" value="Đăng nhập" class="btn-login" />
                </div>
            </form>
        </div>
    </section>

    <!-- ================================================ -->
    <!-- JS PLUGIN CDN MẶC ĐỊNH -->
    <script src="<?php echo $admin_public_dir; ?>/js-plugin/jquery-3.6.1.min.js"></script>
    <!-- JS CUSTOM file -->
    <?php include(APPPATH . 'Views/admin/js/js_login.php'); ?>
</body>

</html>
