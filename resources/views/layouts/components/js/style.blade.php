<script src="{{ asset('templating/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('templating/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('templating/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('templating/js/sb-admin-2.min.js') }}"></script>

<script>
$(document).ready(function() {

    // Set default sidebar
    function setSidebarDefault() {
        if (window.innerWidth <= 1024) {
            // Tablet: hide sidebar minimalis
            $('body').addClass('sidebar-toggled');
            $('#accordionSidebar').addClass('toggled');
        } else {
            // Desktop: show sidebar
            $('body').removeClass('sidebar-toggled');
            $('#accordionSidebar').removeClass('toggled');
        }
    }

    // Jalankan saat load
    setSidebarDefault();

    // Jalankan saat resize
    $(window).resize(function() {
        setSidebarDefault();
    });

    $("#sidebarToggleTop").on('click', function(e) {
        e.preventDefault();
        $("body").toggleClass("sidebar-toggled");
        $("#accordionSidebar").toggleClass("toggled");
    });

});
</script>

@stack('style-js')
