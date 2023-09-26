<?php

/**
 *   Đây phần header chứa logo, menu, user và giỏ hàng (nếu có)
 */

$admin_public_dir = base_url() . '/public/admin';
?>

<header>
    <a href="<?php echo base_url(route_to('admin.khachhang')); ?>" class="logo"><img src="<?php echo $admin_public_dir; ?>/img/safe-logo.png" alt="my-logo" /></a>

    <nav class="navbar">
        <a href="#list-customer" class="active">DS Khách hàng</a>
        <a href="#list-user">Quản trị viên</a>
    </nav>

    <div class="user">
        <div class="avatar"><img src="<?php echo $admin_public_dir; ?>/img/admin.png" alt="my-user" /></div>

        <!-- lấy thông tin admin đăng nhập file admin_helper -->
        <div class="info">
            <?php if ($userInfo = get_admin_info()) : ?>
                <h4><?php echo $userInfo['full_name'] ?></h4>
                <p><?php echo $userInfo['email'] ?></p>
            <?php endif; ?>
        </div>

        <div class="box-logout">
            <a href="<?php echo base_url(route_to('admin.dangxuat')); ?>"><i class="fa-regular fa-right-from-bracket"></i> Đăng xuất</a>
        </div>
    </div>
</header>
