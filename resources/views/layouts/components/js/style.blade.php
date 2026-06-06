<script src="{{ asset('templating/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('templating/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('templating/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('templating/js/sb-admin-2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        const applyTabletLandscapeSidebar = () => {
            const w = window.innerWidth || 0;
            const h = window.innerHeight || 0;
            const isLandscape = window.matchMedia && window.matchMedia('(orientation: landscape)').matches;
            const isTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints && navigator.maxTouchPoints > 0);
            const isTabletLandscape = isTouch && isLandscape && w >= 768 && w <= 1366 && h >= 600;

            if (isTabletLandscape) {
                $("body").addClass("sidebar-toggled");
                $(".sidebar").addClass("toggled");
                $('.sidebar .collapse').collapse('hide');
            }
        };

        applyTabletLandscapeSidebar();

        window.addEventListener('orientationchange', applyTabletLandscapeSidebar);
        window.addEventListener('resize', applyTabletLandscapeSidebar);

    });
</script>

@stack('style-js')
