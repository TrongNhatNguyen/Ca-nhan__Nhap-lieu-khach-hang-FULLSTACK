<script>
    $(document).ready(function() {
        // GÁN GIÁ TRỊ PAGER HIỆN TẠI PAGINATE:
        let pageValue = getParameterByName('page') || 1;

        /* LẮNG NGHE SỰ KIỆN SUBMIT FORM KHÁCH HÀNG === */
        submit_form_listening();

        /* LẮNG NGHE SỰ KIỆN NÚT SỬA Ở TABLE === */
        $(document).on('click', '.btn-upd-act', function(event) {

            $.ajax({
                url: "<?php echo base_url(route_to('khachhang.show_capnhat')); ?>",
                method: 'GET',
                data: {
                    idKh: $(this).closest('tr').attr('id'), // lấy idKh từ <tr> chứa nó
                    page: pageValue // giá trị paginate đang hiển thị
                },
                beforeSend: function() {
                    $('.khachhang-form-spinner').fadeIn();
                },
                success: function(response) {
                    // NẠP DL VÀO FORM HIỂN THỊ
                    var form = $('.form-khachhang');
                    form.siblings('h3').text('SỬA KHÁCH HÀNG #' + response.id); // thay đổi tiêu đề h3 nằm cùng cấp vs form
                    form.attr('action', '<?= base_url() . route_to('khachhang.xuly_capnhat') ?>');
                    form.attr('status', 'update');
                    form.find('input[name="id_kh"]').val(response.id);
                    form.find('input[name="name"]').val(response.name);
                    form.find('input[name="phone"]').val(response.phone);
                    form.find('input[name="points"]').val(response.points);
                    form.find('input[type="submit"]').val('Sửa ngay');

                    // HIGHLIGHT Dòng <tr> đang sửa ở table
                    $('.table-list').find('tbody tr').removeClass('highlighted-row');
                    var highlightRow = $('.table-list').find('tbody #' + response.id);
                    highlightRow.toggleClass('highlighted-row');
                },
                error: function(xhr, status, error) {
                    console.error('Đã xảy ra lỗi từ server.');
                },
                complete: function() {
                    // Dừng loading hoàn thành xử lý
                    setTimeout(function() {
                        $('.khachhang-form-spinner').fadeOut();
                    }, 1000);
                }
            });
        });

        /* LẮNG NGHE SỰ KIỆN NÚT XOÁ Ở TABLE === */
        $(document).on('click', '.btn-del-act', function(event) {
            // Hiện cảnh báo Sweet alert
            Swal.fire({
                title: 'Bạn chắc chứ',
                text: 'Bản ghi nãy sẽ bị xoá vĩnh viễn',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xoá luôn!',
                customClass: {
                    popup: 'my-custom-popup-class',
                },
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?php echo base_url(route_to('khachhang.xoa')); ?>",
                        method: 'GET',
                        data: {
                            idKh: $(this).closest('tr').attr('id'), // lấy idKh từ <tr> chứa nó
                            page: pageValue // giá trị paginate đang hiển thị
                        },
                        success: function(response) {
                            // Hiện thông báo thành công
                            Swal.fire({
                                title: 'Đã xoá!',
                                text: 'quay trở lại thôi.',
                                icon: 'success',
                                customClass: {
                                    popup: 'my-custom-popup-class',
                                },
                            });
                            // LOAD lại table khách hàng
                            table_khachhang_reload_event(response, 'delete');
                        },
                        error: function(xhr, status, error) {
                            // Hiện thông báo thất bại!
                            Swal.fire({
                                title: 'Thất bại!',
                                text: 'quay trở lại thôi.',
                                icon: 'error',
                                customClass: {
                                    popup: 'my-custom-popup-class',
                                },
                            });
                        },
                    });
                };
            });
        });

        /* LẮNG NGHE SỰ KIỆN TÌM KIẾM Ở TABLE === */
        $(document).on('submit', '.form-search', function(event) {
            event.preventDefault();

            formData = $(this).serialize();
            formData += "&page=" + encodeURIComponent(pageValue);

            // GỬI REQUEST AJAX:
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Load lại table danh sách
                    table_khachhang_reload_event(response, 'search');
                },
                error: function(xhr, status, error) {
                    console.error('Đã xảy ra lỗi từ server.');
                },
                complete: function() {
                    // Dừng loading sau khi xong xử lý
                    setTimeout(function() {
                        $('.khachhang-form-spinner').fadeOut();
                    }, 1000);
                }
            });
        });


        /*-----------------------------------------------
            HÀM SỰ KIỆN THÊM, SỬA KHÁCH HÀNG (chỉ gọi)
        ------------------------------------------------*/
        function submit_form_listening() {
            var form = $('.form-khachhang');

            // `form.length > 0` kiểm tra quy tắc trong khi nhập mà ko cần đợi bấm submit
            if (form.length > 0) {

                // Đặt quy tắc xác thực (rules):
                valid_custom();
                form.validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 5,
                            maxlength: 20,
                            noSpecialChars: true
                        },
                        phone: {
                            required: true,
                            digits: true,
                            minlength: 10,
                            maxlength: 10,
                            regex: /^\d{10}$/ // quy tắc SĐT hợp lệ
                        },
                        points: {
                            required: true,
                            minlength: 0
                        }
                    },
                    messages: {
                        name: {
                            required: "Vui lòng nhập tên người dùng",
                            minlength: "Tên người dùng phải có ít nhất 5 ký tự",
                            maxlength: "Tên người dùng chỉ nhiểu nhất 20 ký tự",
                        },
                        phone: {
                            required: "Vui lòng nhập số điện thoại",
                            digits: "Số điện thoại chỉ được chứa các chữ số",
                            minlength: "Số điện thoại phải có ít nhất 10 chữ số",
                            maxlength: "Số điện thoại không được vượt quá 10 chữ số",
                        },
                        points: {
                            required: "Vui lòng nhập điểm",
                            minlength: "Điểm phải lớn hơn hoặc bằng 0"
                        }
                    },
                    submitHandler: function() {

                        formData = form.serialize();
                        formData += "&page=" + encodeURIComponent(pageValue);

                        // GỬI REQUEST AJAX:
                        $.ajax({
                            type: 'POST',
                            url: form.attr('action'),
                            data: formData,
                            dataType: 'json',
                            beforeSend: function() {
                                $('.khachhang-form-spinner').fadeIn();
                            },
                            success: function(response) {
                                // Làm mới form & Load lại table danh sách
                                form[0].reset();

                                if (form.attr('status') === 'update') {
                                    form.siblings('h3').text('THÊM KHÁCH HÀNG MỚI'); // thay đổi tiêu đề h3 nằm cùng cấp vs form
                                    form.attr('action', '<?= base_url() . route_to('khachhang.them_moi') ?>');
                                    form.attr('status', 'add');
                                    form.find('input[type="submit"]').val('Thêm mới');
                                    table_khachhang_reload_event(response, 'update', form.find('[name="id_kh"]').val());
                                } else {
                                    table_khachhang_reload_event(response, 'add');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Đã xảy ra lỗi từ server.');
                            },
                            complete: function() {
                                // Dừng loading sau khi xong xử lý
                                setTimeout(function() {
                                    $('.khachhang-form-spinner').fadeOut();
                                }, 1000);
                            }
                        });
                    }
                });
            }
        }


        /*-----------------------------------------------------
            HÀM LẤY GIÁ TRỊ CỦA BIẾN TRÊN THANH URL (chỉ gọi)
        ------------------------------------------------------*/
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }


        /*------------------------------------
            HÀM CÁC QUY TẮC TỰ TẠO (chỉ gọi)
        -------------------------------------*/
        function valid_custom() {
            // Tự tạo quy tắc không chứa kí tự đặc biệt
            $.validator.addMethod("noSpecialChars", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9\s\u00C0-\u1EF9]+$/u.test(value);
            }, "Tên người dùng không được chứa kí tự đặc biệt.");

            // Tự tạo quy tắc SĐT
            $.validator.addMethod("regex", function(value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Vui lòng nhập giá trị hợp lệ.");
        }


        /*-------------------------------------------
            RELOAD LẠI TABLE KHÁCH HÀNG (chỉ gọi)
        --------------------------------------------*/
        function table_khachhang_reload_event(response, act = null, idKh = null) {
            var tableReload = $('.table-list');

            // Thêm class "loading" áp dụng hiệu ứng load màn trắng
            tableReload.addClass('loading');

            /// XÂY DỰNG HÀM LOAD LẠI BẢNG (Pro)
            setTimeout(function() {
                // Gỡ bỏ class "loading" kết thúc hiệu ứng load màn trắng
                tableReload.removeClass('loading');

                // Xóa nội dung tbody cũ của bảng
                var tableBody = tableReload.find('tbody');
                tableBody.empty();

                // Lấy DL Response để làm nội dung tbody mới
                var html = '';
                var dsKhachhangs = response.dsKhachhangs;
                if (dsKhachhangs.length > 0) {
                    $.each(dsKhachhangs, function(index, khachhang) {
                        html += `
                            <tr id="${khachhang.id}">
                                <td>${khachhang.stt}</td>
                                <td>${khachhang.name}</td>
                                <td>${khachhang.phone}</td>
                                <td>${khachhang.points}</td>
                                <td><input type="checkbox"/></td>
                                <td>${khachhang.created_date}</td>
                                <td>
                                    <button class="btn-upd-act">Sửa</button>
                                    <button class="btn-del-act">Xoá</button>
                                </td>
                            </tr>`;
                    });
                } else {
                    html = `<tr><td colspan="7">Không có khách hàng nào.</td></tr>`;
                }
                // Nạp DL tbody mới này vào bảng:
                tableBody.append(html);

                // Cập nhật paginate:
                $('.paginate-link-container').html(response.pager);

                /// HIGHLIGHT HÀNG DL <tr> ĐƯỢC THAO TÁC:
                var highlightRow = '';

                if (act === 'add') {
                    highlightRow = tableBody.find('tr:first-child');
                } else if (act === 'update' && idKh !== null) {
                    highlightRow = tableBody.find('#' + idKh);
                }

                if (highlightRow.length > 0) {
                    highlightRow.hide().toggleClass('highlighted-row').fadeIn(1000);
                    setTimeout(function() {
                        highlightRow.toggleClass('highlighted-row');
                    }, 3500);
                }
            }, 1000);
        }

    }); /// END DOCUMENT!!
</script>
