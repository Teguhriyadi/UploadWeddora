<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TitipKado\CreateRequest;
use App\Http\Requests\TitipKado\UpdateRequest;
use App\Models\Guest;
use App\Models\SouvenirDeposit;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SouvenirDepositController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = SouvenirDeposit::with(["guest:id,nama_tamu,nama_undangan", "petugas:id,nama"]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_tamu', function ($row) {
                    if ($row->guest_id != NULL) {
                        return $row->guest->nama_tamu . " - " . $row->guest->nama_undangan;
                    } else if ($row->guest_id == NULL) {
                        return $row->nama_tamu;
                    }
                })
                ->addColumn("foto", function ($row) {
                    if (empty($row->foto)) {
                        return '-';
                    }

                    $url = Storage::disk('s3')->url('souvenir/' . $row->foto);

                    return '<img src="' . $url . '" width="60" height="60" style="object-fit:cover; border-radius:6px;">';
                })
                ->addColumn("waktu_dititipkan", function ($row) {
                    return \Carbon\Carbon::parse($row->waktu_dititipkan)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i:s');
                })
                ->addColumn("waktu_diterima_pengantin", function ($row) {
                    if ($row->waktu_diterima_pengantin == NULL) {
                        return "-";
                    } else {
                        return \Carbon\Carbon::parse($row->waktu_diterima_pengantin)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i:s');
                    }
                })
                ->addColumn("petugas", function ($row) {
                    return $row->petugas->nama;
                })
                ->addColumn('status', function ($row) {

                    if ($row->status == "DITITIPKAN") {
                        return "Di Titipkan di Meja Tamu";
                    } else if ($row->status == "SUDAH_DITERIMA_PENGANTIN") {
                        return "Sudah Diterima";
                    }
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <a href="/modules/titip-kado/' . $row->id . '/edit" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="/modules/titip-kado/' . $row->id . '" method="POST" style="display:inline;" class="delete-form">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['status', 'action', 'foto', 'checkbox'])
                ->make(true);
        }

        return view("modules.master.souvenir-guest.index");
    }

    public function create()
    {
        $data["guest"] = Guest::get(['*']);

        return view("modules.master.souvenir-guest.create", $data);
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            if ($request->foto) {
                $foto = ImageHelper::uploadFileToS3Souvenir($request->foto);
            } else {
                $foto = NULL;
            }

            ActivityLogger::setContext('Titip Kado', 'create', [
                'guest_id' => $request->guest_id,
                'foto_uploaded' => (bool) $request->foto,
            ]);
            SouvenirDeposit::create([
                'guest_id' => $request->guest_id,
                'nama_tamu' => $request->nama_tamu,
                "nama_kado" => $request->nama_kado,
                "qty" => $request->qty,
                "keterangan" => $request->keterangan,
                "foto" => $foto,
                "waktu_dititipkan" => date("Y-m-d H:i:s"),
                "petugas_id" => Auth::user()->id
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

            $data["guest"] = Guest::get(['*']);
            $data["edit"] = SouvenirDeposit::where("id", "=", $id, "and")->first(['*']);

            DB::commit();

            return view("modules.master.souvenir-guest.edit", $data);
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::beginTransaction();

            $deposit = SouvenirDeposit::where("id", "=", $id, "and")->first(['*']);
            if (!$deposit) {
                DB::rollBack();
                return back()->with("error", "Data tidak ditemukan");
            }

            $foto = $deposit->foto;
            $fotoChanged = false;
            if ($request->foto) {
                if ($deposit->foto) {
                    Storage::disk('s3')->delete('souvenir/' . $deposit->foto);
                }

                $foto = ImageHelper::uploadFileToS3Souvenir($request->foto);
                $fotoChanged = true;
            }

            ActivityLogger::setContext('Titip Kado', 'update', [
                'souvenir_deposit_id' => $deposit->id,
                'foto_changed' => $fotoChanged,
            ]);
            $deposit->update([
                'guest_id' => $request->guest_id,
                'nama_tamu' => $request->nama_tamu,
                "nama_kado" => $request->nama_kado,
                "qty" => $request->qty,
                "keterangan" => $request->keterangan,
                "foto" => $foto,
                "petugas_id" => Auth::user()->id
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

            $cek = SouvenirDeposit::where("id", "=", $id, "and")->first(['*']);
            if (!$cek) {
                DB::rollBack();
                return back()->with("error", "Data tidak ditemukan");
            }

            if ($cek->foto) {
                Storage::disk('s3')->delete('souvenir/' . $cek->foto);
            }

            ActivityLogger::setContext('Titip Kado', 'delete', [
                'souvenir_deposit_id' => $cek->id,
            ]);
            $cek->delete();

            DB::commit();

            return back()->with("success", "Data Berhasil di Hapus");
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with("error", $e->getMessage());
        }
    }

}
