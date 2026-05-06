<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
        {{ config('app.name') }} - @stack("title-modules")
    </title>

    @include("layouts.components.css.style")

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include("layouts.components.sidebar.v_sidebar")

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                @include("layouts.components.navbar.v_navbar")

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">
                        @stack("title-modules")
                    </h1>

                    @stack("content-modules")
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            @include("layouts.components.footer.v_footer")

        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @include("layouts.components.modals.v_modal")

    @include("layouts.components.js.style")

</body>

</html>
