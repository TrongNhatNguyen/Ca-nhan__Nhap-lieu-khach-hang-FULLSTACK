<?php

/**
 *  Đây là file chứa bộ khung của webstie, nơi chứa
 *  các đường link CSS, JS,.. và các link khác, bất kỳ 
 *  thành phần giao diện nào khi được khai báo section
 *  ở index dều phải được khai báo vị trí hiển thị renderSection ở đây.
 */

?>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vlance Nhập Liệu KH</title>

    <!-- CSS CDN Plugin files -->
    <link href="<?= base_url(); ?>/public/admin/icon/font-awesome-6.4.2-pro-main/css/all.css" rel="stylesheet" />

    <!-- Hộp thoại thông báo (SweetAlerts) đẹp mắt -->
    <link href="<?= base_url(); ?>/public/admin/sweet-alert2/sweetalert2.min.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>/public/admin/sweet-alert2/style-sweetalert2.css" rel="stylesheet" />

    <!-- CSS CUSTOM -->
    <link href="<?= base_url(); ?>/public/admin/css/style-index.css" rel="stylesheet" />

    <!-- Hiệu ứng loading -->
    <link href="<?= base_url(); ?>/public/admin/css/spinner-loading.css" rel="stylesheet" />

    <!-- Phân trang Paginate links -->
    <link href="<?= base_url(); ?>/public/admin/css/paginate.css" rel="stylesheet" />
</head>

<body>
    <!-- === VỊ TRÍ NHẬN KHAI BÁO FOOTER === -->
    <?= $this->renderSection('header') ?>

    <!-- === VỊ TRÍ NHẬN KHAI BÁO CONTENT PAGES ===  -->
    <?= $this->renderSection('PAGE_CONTENT') ?>

    <!-- === VỊ TRÍ NHẬN KHAI BÁO FOOTER === -->
    <?= $this->renderSection('footer') ?>

    <!-- Hiệu ứng load trang - spinner-loading -->
    <?php include('spinner_loading.php') ?>

    <!-- ============================================= -->
    <!-- JS PLUGIN CDN MẶC ĐỊNH -->
    <script src="<?= base_url(); ?>/public/admin/js-plugin/jquery-3.6.1.min.js"></script>

    <!-- Quy tắc xác thực Form input -->
    <script src="<?= base_url(); ?>/public/admin/js-plugin/jquery.validate.min.js"></script>

    <!-- Hộp thoại thông báo (SweetAlerts) đẹp mắt -->
    <script src="<?= base_url(); ?>/public/admin/sweet-alert2/sweetalert2.min.js"></script>

    <!-- Nếu trang đó có file JS riêng thì nhận khai báo ở dưới đây  -->
    <?= $this->renderSection('custom_js_page') ?>

    <script>
        /*----------------------------------------------
            HIỂN THỊ WEB SAU KHI LOAD XONG TẤT CẢ JS
        -----------------------------------------------*/
        $(window).on('beforeunload', function() {
            $('.spinner-loading').fadeIn();
        });
        window.onload = function() {
            setTimeout(function() {
                $('.spinner-loading').fadeOut();
                $('.khachhang-form-spinner').fadeOut();
            }, 1200);
        };
    </script>
</body>

</html>
