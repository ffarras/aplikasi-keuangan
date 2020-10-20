<?php

namespace App\Http\Controllers;

use App\Account;
use App\Hutang;
use App\Kategori;
use App\Pegawai;
use App\Pengeluaran;
use Illuminate\Http\Request;
use File;
use DataTables;
use Carbon\Carbon;
use DB;

class HutangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hutang = Hutang::get();

        return view('hutang.index', compact('hutang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account = Account::all();
        $pegawai = Pegawai::all();
        $kategori = Kategori::all();
        return view('hutang.create', compact('account', 'pegawai', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'admin' => 'required',
                'tanggal' => 'required',
                'pegawai' => 'required',
                'kategori' => 'required',
                'aktivitas' => 'required|regex:/^[A-Za-z&-\/0-9 ]+$/',
                'jumlah' => 'required',
                'bukti.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]
        );

        try {
            DB::beginTransaction();
            if ($request->hasfile('bukti')) {
                foreach ($request->file('bukti') as $file) {
                    $namafile = time() . '-' . $file->getClientOriginalName();
                    $folderhut = 'uploads/hutang/';
                    $folderkel = 'uploads/pengeluaran/';
                    $file->move('uploads/hutang', $namafile);
                    copy($folderhut . $namafile, $folderkel . $namafile);
                    $data[] = $namafile;
                }

                $upload = json_encode($data);
            }

            $request->tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');

            Hutang::create(
                [
                    'user_id' => $request->admin,
                    'tanggal' => $request->tanggal,
                    'account_id' => $request->account,
                    'pegawai_id' => $request->pegawai,
                    'kategori_id' => $request->kategori,
                    'aktivitas' => $request->aktivitas,
                    'jumlah' => str_replace(',', '', $request->jumlah),
                    'bukti' => empty($upload) ? '' : $upload,
                    'catatan' => empty($request->catatan) ? '' : $request->catatan,
                    'status' => $request->status,
                ]
            );

            Pengeluaran::create(
                [
                    'user_id' => $request->admin,
                    'tanggal' => $request->tanggal,
                    'account_id' => $request->account,
                    'kategori_id' => $request->kategori,
                    'aktivitas' => $request->aktivitas,
                    'jumlah' => str_replace(',', '', $request->jumlah),
                    'bukti' => empty($upload) ? '' : $upload,
                    'catatan' => empty($request->catatan) ? '' : $request->catatan,
                ]
            );

            $account = Account::where('id', $request->account)->first();
            $account->saldo = $account->saldo - str_replace(',', '', $request->jumlah);
            $account->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        return redirect('/hutang')->withStatus('Reimburse berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Hutang  $hutang
     * @return \Illuminate\Http\Response
     */
    public function show(Hutang $hutang)
    {
        return view('hutang.show', compact('hutang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hutang  $hutang
     * @return \Illuminate\Http\Response
     */
    public function edit(Hutang $hutang)
    {
        $kategori = Kategori::all();
        $account = Account::all();
        $pegawai = Pegawai::all();
        return view('hutang.edit', compact('hutang', 'pegawai', 'kategori', 'account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Hutang  $hutang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hutang $hutang)
    {
        $request->validate(
            [
                'status' => 'required',
                'admin' => 'required',
                'tanggal' => 'required',
                'account' => 'required',
                'pegawai' => 'required',
                'kategori' => 'required',
                'aktivitas' => 'required|regex:/^[A-Za-z0-9&-\/ ]+$/',
                'jumlah' => 'required',
                'bukti.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]
        );

        $file = null;

        try {
            DB::beginTransaction();
            $hutang = Hutang::find($request->id);
            $pengeluaran = Pengeluaran::where([
                ['aktivitas', $hutang->aktivitas],
                ['created_at', $hutang->created_at],
            ])->first();
            $account = Account::where('id', $hutang->account_id)->first();
            $upload = $hutang->bukti;

            if ($request->imagecheck) {
                $upload = $this->checkImage($request, $hutang, $upload, $file);
            }

            if (($request->hasfile('bukti')) && (empty($request->imagecheck))) {
                $upload = $this->emptyCheck($request, $hutang, $upload, $file);
            }

            $this->saveUpdate($request, $hutang, $pengeluaran, $account, $upload);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        return redirect()->route('hutang.index')->withStatus('Reimburse berhasil diupdate');
    }

    public function checkImage(Request $request, Hutang $hutang, $upload, $file)
    {
        if ($request->hasfile('bukti')) {
            foreach (json_decode($hutang->bukti) as $foto) {
                $data[] = $foto;
            }

            foreach ($request->imagecheck as $check) {
                $data = array_diff($data, array($check));
                File::delete('uploads/hutang/' . $check);
                File::delete('uploads/pengeluaran/' . $check);
            }

            foreach ($request->file('bukti') as $file) {
                $namafile = time() . '-' . $file->getClientOriginalName();
                $folderhut = 'uploads/hutang/';
                $folderkel = 'uploads/pengeluaran/';
                $file->move('uploads/hutang', $namafile);
                copy($folderhut . $namafile, $folderkel . $namafile);
                $data[] = $namafile;
            }
        } else {
            foreach (json_decode($hutang->bukti) as $foto) {
                $data[] = $foto;
            }

            foreach ($request->imagecheck as $check) {
                $data = array_diff($data, array($check));
                File::delete('uploads/hutang/' . $check);
                File::delete('uploads/pengeluaran/' . $check);
            }
        }
        $upload = json_encode($data);

        return $upload;
    }

    public function emptyCheck(Request $request, Hutang $hutang, $upload, $file)
    {
        if ($hutang->bukti) {
            foreach (json_decode($hutang->bukti) as $foto) {
                $data[] = $foto;
            }
        }

        foreach ($request->file('bukti') as $file) {
            $namafile = time() . '-' . $file->getClientOriginalName();
            $folderhut = 'uploads/hutang/';
            $folderkel = 'uploads/pengeluaran/';
            $file->move('uploads/hutang', $namafile);
            copy($folderhut . $namafile, $folderkel . $namafile);
            $data[] = $namafile;
        }

        $upload = json_encode($data);

        return $upload;
    }

    public function saveUpdate(Request $request, Hutang $hutang, Pengeluaran $pengeluaran, Account $account, $upload)
    {
        if ($account->id <> $request->account) {
            $accountbaru = Account::where('id', $request->account)->first();
            $account->saldo = $account->saldo + str_replace(',', '', $hutang->jumlah);
            $accountbaru->saldo = $accountbaru->saldo - str_replace(',', '', $request->jumlah);
            $accountbaru->save();
        } else {
            $account->saldo = ($account->saldo + str_replace(',', '', $hutang->jumlah)) - str_replace(',', '', $request->jumlah);
        }

        $account->save();

        $hutang->status = $request->status;
        $hutang->user_id = $request->admin;
        $hutang->tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
        $hutang->account_id = $request->account;
        $hutang->kategori_id = $request->kategori;
        $hutang->pegawai_id = $request->pegawai;
        $hutang->aktivitas = $request->aktivitas;
        $hutang->jumlah = str_replace(',', '', $request->jumlah);
        $hutang->bukti = $upload;
        $hutang->catatan = empty($request->catatan) ? '' : $request->catatan;
        $hutang->save();

        $pengeluaran->user_id = $request->admin;
        $pengeluaran->tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
        $pengeluaran->account_id = $request->account;
        $pengeluaran->kategori_id = $request->kategori;
        $pengeluaran->aktivitas = $request->aktivitas;
        $pengeluaran->jumlah = str_replace(',', '', $request->jumlah);
        $pengeluaran->bukti = $upload;
        $pengeluaran->catatan = empty($request->catatan) ? '' : $request->catatan;
        $pengeluaran->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hutang  $hutang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hutang $hutang)
    {
        try {
            DB::beginTransaction();
            if ($hutang->bukti) {
                foreach (json_decode($hutang->bukti) as $foto) {
                    File::delete('uploads/hutang/' . $foto);
                    File::delete('uploads/pengeluaran/' . $foto);
                }
            }
            $pengeluaran = Pengeluaran::where([
                ['aktivitas', $hutang->aktivitas],
                ['created_at', $hutang->created_at],
            ]);

            $account = Account::where('id', $hutang->account_id)->first();
            $account->saldo = $account->saldo + str_replace(',', '', $hutang->jumlah);
            $account->save();

            $hutang->delete();
            if ($pengeluaran) {
                $pengeluaran->delete();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        return redirect()->route('hutang.index')->withStatus('Reimburse berhasil dihapus');
    }

    public function dataTable()
    {
        $model = Hutang::with('pegawai')->with('kategori');

        $start_date = (!empty(filter_input(INPUT_GET, 'start_date'))) ? (filter_input(INPUT_GET, 'start_date')) : ('');
        $end_date = (!empty(filter_input(INPUT_GET, 'end_date'))) ? (filter_input(INPUT_GET, 'end_date')) : ('');

        if ($start_date && $end_date) {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));

            $model->whereRaw("date(hutang.tanggal) >= '" . $start_date . "' AND date(hutang.tanggal) <= '" . $end_date . "'");
        }


        return DataTables::of($model)
            ->addColumn('action', function ($model) {
                $action = '';
                if ($model->status == "Clear") {
                    $action .= '
                    <a href="' . route('hutang.show', $model->id) . '" class="btn btn-success btn-xs"><i class="la flaticon-search-2"></i></a>
                    <button class="btn btn-xs btn-danger btn-delete" data-remote="/hutang/' . $model->id . '"><i class="fas fa-trash"></i></button>';
                } else {
                    $action .= '<a href="' . route('hutang.show', $model->id) . '" class="btn btn-success btn-xs"><i class="la flaticon-search-2"></i></a>
                    <a href="' . route('hutang.edit', $model->id) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a>  
                    <button class="btn btn-xs btn-danger btn-delete" data-remote="/hutang/' . $model->id . '"><i class="fas fa-trash"></i></button>';
                }
                return $action;
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
