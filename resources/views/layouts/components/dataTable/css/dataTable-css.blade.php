<link href="{{ asset('templating/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<style>
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        border-radius: 12px;
    }

    #dataTable {
        width: 100% !important;
    }

    #dataTable th,
    #dataTable td {
        white-space: nowrap;
        vertical-align: middle;
    }

    #dataTable thead th {
        background: #f8f9fc;
    }

    div.dataTables_wrapper {
        width: 100%;
    }

    div.dataTables_wrapper .dataTables_length,
    div.dataTables_wrapper .dataTables_filter {
        margin-bottom: 15px;
    }

    div.dataTables_wrapper .dataTables_paginate {
        margin-top: 15px;
    }

    div.dataTables_wrapper .dataTables_info {
        padding-top: 15px;
    }

    @media (max-width: 768px) {

        div.dataTables_wrapper .dataTables_length,
        div.dataTables_wrapper .dataTables_filter,
        div.dataTables_wrapper .dataTables_info,
        div.dataTables_wrapper .dataTables_paginate {
            text-align: center;
            float: none !important;
        }

        div.dataTables_wrapper .dataTables_filter input {
            width: 100%;
            margin-left: 0 !important;
            margin-top: 10px;
        }
    }
</style>
