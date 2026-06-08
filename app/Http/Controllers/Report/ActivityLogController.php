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

            $data = ActivityLog::orderBy("created_at", "DESC");

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
                ->rawColumns(['logged_at', 'created_at', 'updated_at'])
                ->make(true);
        }

        return view("modules.report.activity-log.index");
    }
}
