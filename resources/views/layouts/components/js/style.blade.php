<script src="{{ asset('templating/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('templating/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('templating/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('templating/js/sb-admin-2.min.js') }}"></script>

<script>
    $(document).ready(function() {

        if (window.innerWidth <= 1024) {
            $("body").addClass("sidebar-toggled");
            $(".sidebar").addClass("toggled");
        }

    });
</script>

@stack('style-js')
