<?php

namespace App\Http\Controllers\LandingPage;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tema\CreateRequest;
use App\Http\Requests\Tema\UpdateRequest;
use App\Models\LPKategori;
use App\Models\LPTema;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TemaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = LPTema::with(["kategori:id,nama_kategori"])->orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kategori', function ($row) {
                    return $row->kategori->nama_kategori;
                })
                ->addColumn("image", function ($row) {

                    if (empty($row->img_bg)) {
                        return '-';
                    }

                    $url = Storage::disk('s3')->url('souvenir/' . $row->img_bg);

                    return '
                        <img
                            src="'.$url.'"
                            class="preview-image"
                            data-image="'.$url.'"
                            width="60"
                            height="60"
                            style="
                                object-fit:cover;
                                border-radius:6px;
                                cursor:pointer;
                            ">
                    ';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/landing-page/tema/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/landing-page/tema/' . $row->id . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button onclick="return confirm(\'Yakin? Apakah Anda Ingin Menghapus Data Ini?\')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        return view("modules.master.tema.index");
    }

    public function create()
    {
        try {
            DB::beginTransaction();

            $data["kategori"] = LPKategori::get(["*"]);

            DB::commit();

            return view("modules.master.tema.create", $data);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with("error", $e->getMessage());
        }
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            if ($request->img_bg) {
                $foto = ImageHelper::uploadFileToS3Souvenir($request->img_bg);
            } else {
                $foto = NULL;
            }

            ActivityLogger::setContext('Master Landing Page Tema', 'create', [
                'nama' => $request->nama,
                'subtitle' => $request->subtitle
            ]);

            LPTema::create([
                "nama" => $request->nama,
                "subtitle" => $request->subtitle,
                "deskripsi" => $request->deskripsi,
                "badge" => $request->badge,
                "lp_kategori_id" => $request->lp_kategori_id,
                "img_bg" => $foto
            ]);

            DB::commit();

            return back()->with("success", "Data Berhasil di Tambahkan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {

            DB::beginTransaction();

            $data["edit"] = LPTema::where("id", "=", $id, "and")->first(['*']);
            $data["kategori"] = LPKategori::get(["*"]);

            DB::commit();

            return view("modules.master.tema.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function datatable(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = LPKategori::where("id", "!=", $id, "and")
                ->orderBy('created_at', 'DESC');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        return '
                        <button class="btn btn-success btn-sm btn-toggle-status"
                            data-id="' . $row->id . '"
                            data-status="0">
                            <i class="fa fa-check"></i> Aktif
                        </button>
                    ';
                    }

                    return '
                    <button class="btn btn-danger btn-sm btn-toggle-status"
                        data-id="' . $row->id . '"
                        data-status="1">
                        <i class="fa fa-times"></i> Non Aktif
                    </button>
                ';
                })

                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/landing-page/kategori/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/landing-page/kategori/' . $row->id . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button onclick="return confirm(\'Yakin? Apakah Anda Ingin Menghapus Data Ini?\')" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $tema = LPTema::where("id", "=", $id, "and")->first(['*']);
            ActivityLogger::setContext('Master Landing Page Tema', 'update', [
                'tema_id' => $tema?->id,
            ]);
            $tema->update([
                "nama" => $request->nama,
                "subtitle" => $request->subtitle,
                "deskripsi" => $request->deskripsi,
                "badge" => $request->badge,
                "lp_kategori_id" => $request->lp_kategori_id
            ]);

            DB::commit();

            return back()->with("success", "Data Berhasil di Simpan");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $tema = LPTema::where("id", "=", $id, "and")->first(['*']);
            if ($tema) {
                ActivityLogger::setContext('Master Landing Page Tema', 'delete', [
                    'tema_id' => $tema->id,
                ]);
                $tema->delete();
            }

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }
}
