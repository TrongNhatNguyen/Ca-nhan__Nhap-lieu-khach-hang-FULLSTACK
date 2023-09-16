<script>
    $(document).ready(function() {

        /* LẮNG NGHE SỰ KIỆN SUBMIT FORM KHÁCH HÀNG === */
        create_or_update_khachhang();

        /* LẮNG NGHE SỰ KIỆN CLICK NÚT SỬA,XOÁ KHÁCH HÀNG === */
        // $(document).on('click', '.btn-act', function(event) {
        $('.btn-act').click(function() {

            // lấy idKh từ <tr> chứa nó:
            var idKh = $(this).closest('tr').attr('id');
            console.log(idKh);

        });


        /*---------------------------------------------------
            AJAX - HÀM SỰ KIỆN CRUD KHÁCH HÀNG (chỉ gọi)
        ----------------------------------------------------*/
        function create_or_update_khachhang(ID_kh = null) {

            var form = $('.form-khachhang');

            if (form.length > 0) { // `form.length > 0` kiểm tra quy tắc trong khi nhập mà ko cần đợi bấm submit

                // Tự tạo quy tắc không chứa kí tự đặc biệt
                $.validator.addMethod("noSpecialChars", function(value, element) {
                    return this.optional(element) || /^[a-zA-Z0-9\s\u00C0-\u1EF9]+$/u.test(value);
                }, "Tên người dùng không được chứa kí tự đặc biệt.");

                // Tự tạo quy tắc SĐT
                $.validator.addMethod("regex", function(value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                }, "Vui lòng nhập giá trị hợp lệ.");

                // Đặt quy tắc xác thực (rules):
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
                    submitHandler: function(element) {
                        // Lấy dữ liệu từ form
                        var formData = $(element).serialize();

                        // Lấy url thuộc tính "action" của form
                        var URL_action = "<?= base_url() . route_to('khachhang.themmoi') ?>";
                        if (ID_kh != null) {
                            URL_action = "<?= base_url() . route_to('khachhang.capnhat') ?>";
                        }

                        // Gửi request AJAX
                        ajax_form_request_event(URL_action, formData, element);
                    }
                });
            }
        }


        /*---------------------------------------------------
            AJAX - GỬI REQUEST TỚI CONTROLLER (chỉ gọi)
        ----------------------------------------------------*/
        function ajax_form_request_event(URL_action, formData = null, form_reset = null) {

            // Gửi request AJAX
            $.ajax({
                url: URL_action, // Action Url của form
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    $('.khachhang-form-spinner').fadeIn();
                },
                success: function(response) {
                    // Xử lý dữ liệu trả về
                    console.log(response.status, response.message);

                    // Làm mới form cho lần nhập liệu tới (nếu có)
                    if (form_reset != null) {
                        $(form_reset)[0].reset();
                    }

                    // Load lại table:
                    table_khachhang_reload_event(response.listData);
                },
                error: function(xhr, status, error) {
                    console.error('Đã xảy ra lỗi từ server.');
                },
                complete: function() {
                    // Thực hiện các tác vụ sau khi request đã hoàn thành xử lý
                    setTimeout(function() {
                        $('.khachhang-form-spinner').fadeOut();
                    }, 1000);
                }
            });
        }


        /*---------------------------------------------------
            RELOAD LẠI TABLE KHÁCH HÀNG (chỉ gọi)
        ----------------------------------------------------*/
        function table_khachhang_reload_event(listData, idKh = null) {

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
                $.each(listData, function(index, khachhang) {
                    html += `
                        <tr id="${khachhang.id}">
                            <td>${khachhang.stt}</td>
                            <td>${khachhang.name}</td>
                            <td>${khachhang.phone}</td>
                            <td>${khachhang.points}</td>
                            <td><input type="checkbox"/></td>
                            <td>${khachhang.created_date}</td>
                            <td>
                                <button class="btn-act" btn-act="update">Sửa</button>
                                <button class="btn-act rel" btn-act="remove">Xoá</button>
                            </td>
                        </tr>`;
                });
                // Nạp DL tbody mới này vào bảng:
                tableBody.append(html);

                /// HIGHLIGHT HÀNG DL <tr> ĐƯỢC THAO TÁC:
                // nếu mục đích là thêm mới  ->  lấy <tr> mới nhất:
                if (idKh === null) {
                    var highlightRow = tableBody.find('tr:first-child');
                }

                // nếu mục đích là sửa, xoá  ->  lấy <tr> đã chọn theo idKh:
                else {
                    // lọc lấy <tr> đã chọn bằng idKh
                    var $targetRow = tableBody.filter(function() {
                        return $(this).attr('id') === idKh;
                    });

                    // nạp vào biến `highlightRow` để tạo highLight
                    if ($targetRow.length > 0) {
                        var highlightRow = $targetRow;
                        console.log("Có thẻ tr >", highlightRow);
                    } else {
                        console.log("Không tìm thấy thẻ tr nào được chọn");
                    }
                }

                /// TẠO HIGLIGHT BẰNG CSS:
                highlightRow.hide();
                highlightRow.toggleClass('highlighted-row');
                highlightRow.fadeIn(1000);
                setTimeout(function() {
                    highlightRow.toggleClass('highlighted-row');
                }, 2500);
            }, 1000);
        }

        /// END DOCUMENT!!
    });
</script>
