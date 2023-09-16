<script>
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
