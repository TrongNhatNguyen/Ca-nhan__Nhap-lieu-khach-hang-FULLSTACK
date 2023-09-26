<script>
    $(document).ready(function() {
        /* LẮNG NGHE SỰ KIỆN SUBMIT FORM ĐĂNG NHẬP === */
        $(document).on('submit', '.form-login', function(event) {
            event.preventDefault();
            var form = $(this);

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = response.url;
                    } else if (response.status === 'errors') {
                        alert(response.mess);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Đã xảy ra lỗi từ server.');
                }
            });
        });
    }); // END DOCUMENT!


    /*------------------------------------------------
        hiển thị password ở LOGIN
    -------------------------------------------------*/
    function toggleShowPassword() {
        let showPassBtn = document.querySelector('.show-pass-icon');
        var x = document.getElementById('password');
        showPassBtn.classList.toggle('fa-eye');
        if (x.type === 'password') {
            x.type = 'text';
        } else {
            x.type = 'password';
        }
    }
</script>
