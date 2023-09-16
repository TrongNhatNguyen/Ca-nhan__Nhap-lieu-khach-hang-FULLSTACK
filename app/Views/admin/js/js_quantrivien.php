<script>
    /*------------------------------------------------
        hiển thị password ở thêm QUẢN TRỊ VIÊN
    -------------------------------------------------*/
    function toggleShowPassword(element) {
        // thay icon cho phần tử được click
        element.classList.toggle('fa-eye');

        // lấy IDinput từ phần tử được click
        var id = element.getAttribute('inputID');

        // thay đổi type của input được xác định
        var input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }
</script>
