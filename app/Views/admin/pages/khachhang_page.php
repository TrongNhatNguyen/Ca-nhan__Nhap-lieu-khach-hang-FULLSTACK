<?php

/**
 *   Đây là trang index dùng để hiển thị website,
 *   Làm việc trực tiếp với các $data từ Controller truyền đến,
 *   khai báo vị trí xuất hiện trong file: layouts/base.php
 */
?>

<!-- GỌI BỘ KHUNG RA ĐỂ NẠP SECTION PHÍA DƯỚI -->
<?= $this->extend('../layouts/base') ?>

<!-- ======================================== -->
<!-- === MENU - HEADER === -->
<?= $this->section('header') ?>
<?= $this->include('../layouts/header') ?>
<?= $this->endSection() ?>

<!-- === PAGE CONTENTS === -->
<?= $this->section('PAGE_CONTENT') ?>
<section class="section">
    <div class="container">
        <!-- khung trái -->
        <div class="left-box">
            <div class="content">
                <h3 class="title">Thêm Khách hàng mới</h3>
                <form class="form-khachhang" status="add" action="<?php echo base_url(route_to('admin.themmoi_khachhang')); ?>" method="POST">
                    <div class="form-group">
                        <label for="name">Tên khách hàng</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên.." />
                    </div>
                    <div class="row">
                        <div class="form-group phone">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="+84.." />
                        </div>
                        <div class="form-group points">
                            <label for="points">Tích điểm</label>
                            <input type="number" name="points" id="points" class="form-control" placeholder="điểm.." min="0" value="10" />
                        </div>
                    </div>
                    <input type="hidden" name="id_kh">
                    <!-- nút -->
                    <div class="form-submit">
                        <input type="submit" value="Thêm mới" class="btn-add" />
                    </div>

                </form>
            </div>

            <!-- Spinner load khi bấm submit -->
            <div class="khachhang-form-spinner">
                <div class="box-spinner">
                    <div class='ring my-color'></div>
                    <div id="content">
                        <span>Đang xử lý..</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- khung phải -->
        <div class="right-box">
            <div class="content">
                <form class="form-search" method="GET">
                    <input type="search" name="search" id="search-input" placeholder="Tìm kiếm.." autofocus />
                    <button type="submit" for="search-input" class="fas fa-magnifying-glass"></button>
                </form>
                <table class="table-list">
                    <thead>
                        <th>STT</th>
                        <th>Tên khách hàng</th>
                        <th>SĐT</th>
                        <th>điểm</th>
                        <th>chọn</th>
                        <th>Ngày thêm</th>
                        <th>Action</th>
                    </thead>
                    <tbody>

                        <!-- bắt đầu câu điều kiện -->
                        <?php if (!empty($dsKhachhangs)) : ?>
                            <?php foreach ($dsKhachhangs as $khachhang) : ?>
                                <tr id="<?php echo $khachhang['id'] ?>">
                                    <td><?php echo $khachhang['stt']; ?></td>
                                    <td><?php echo $khachhang['name']; ?></td>
                                    <td><?php echo $khachhang['phone']; ?></td>
                                    <td><?php echo $khachhang['points']; ?></td>
                                    <td><input type="checkbox" /></td>
                                    <td><?php echo $khachhang['created_date']; ?></td>
                                    <td>
                                        <button class="btn-upd-act">Sửa</button>
                                        <button class="btn-del-act">Xoá</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7">
                                    <p>Không có khách hàng nào.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <!-- kết thúc câu điều kiện -->

                    </tbody>
                </table>
                <!-- pagination -->
                <div class="paginate-link-container">
                    <?php if (isset($pager)) : ?>
                        <?php echo $pager ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<!-- === FOOTER === -->
<?= $this->section('footer') ?>
<?= $this->include('../layouts/footer') ?>
<?= $this->endSection() ?>

<!-- ======================================= -->
<!-- TẤT CẢ CÁC FILE JS TỰ VIẾT - JS CUSTOM  -->
<?= $this->section('custom_js_page') ?>
<?= $this->include('../js/js_khachhang') ?>
<?= $this->endSection() ?>
