<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = ActivityLog::with(["user:id,nama"])->orderBy("created_at", "DESC");

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("logged_at", function ($row) {
                    return \Carbon\Carbon::parse($row->logged_at)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i:s');
                })
                ->addColumn("created_at", function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i:s');
                })
                ->addColumn("updated_at", function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i:s');
                })
                ->editColumn('user', function ($row) {
                    return "<span class='badge bg-success text-white'>" . $row->nama . "</span>";
                })
                ->editColumn("method", function ($row) {
                    return "<span class='badge bg-success text-white'>" . $row->method . "</span>";
                })
                ->editColumn("subject_type", function ($row) {
                    return "<span class='badge bg-primary text-white'>" . $row->subject_type . "</span>";
                })
                ->editColumn("ip", function ($row) {
                    return "<span class='badge bg-warning text-white'>" . $row->ip . "</span>";
                })
                ->rawColumns(['logged_at', 'user', 'created_at', 'updated_at', 'method', 'subject_type', 'ip'])
                ->make(true);
        }

        return view("modules.report.activity-log.index");
    }
}
