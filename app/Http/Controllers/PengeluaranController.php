<?php

namespace App\Http\Controllers;

use App\Pengeluaran;
use App\Kategori;
use App\User;
use App\Account;
use App\Hutang;
use Illuminate\Http\Request;
use File;
use DataTables;
use Carbon\Carbon;
use DB;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengeluaran = Pengeluaran::get();

        return view('pengeluaran.index', compact('pengeluaran'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = Kategori::all();
        $account = Account::all();
        return view('pengeluaran.create', compact('kategori', 'account'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'admin' => 'required',
            'tanggal' => 'required',
            'account' => 'required',
            'kategori' => 'required',
            'aktivitas' => 'required|regex:/^[A-Za-z&-\/0-9 ]+$/',
            'jumlah' => 'required',
            'bukti' => 'required',
            'bukti.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();
            if ($request->hasfile('bukti')) {
                foreach ($request->file('bukti') as $file) {
                    $namafile = time() . '-' . $file->getClientOriginalName();
                    $file->move('uploads/pengeluaran', $namafile);
                    $data[] = $namafile;
                }

                $upload = json_encode($data);
            }
            
            $request->tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');

            Pengeluaran::create(
                [
                'user_id' => $request->admin,
                'tanggal' => $request->tanggal,
                'account_id' => $request->account,
                'kategori_id' => $request->kategori,
                'aktivitas' => $request->aktivitas,
                'jumlah'=> str_replace( ',', '', $request->jumlah),
                'bukti' => $upload,
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

        return redirect('/pengeluaran')->withStatus('Pengeluaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function show(Pengeluaran $pengeluaran)
    {
        return view('pengeluaran.show', compact('pengeluaran'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengeluaran $pengeluaran)
    {
        $kategori = Kategori::all();
        $account = Account::all();
        return view('pengeluaran.edit', compact('pengeluaran', 'kategori', 'account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'admin' => 'required',
            'tanggal' => 'required',
            'account' => 'required',
            'kategori' => 'required',
            'aktivitas' => 'required|regex:/^[A-Za-z&-\/0-9 ]+$/',
            'jumlah' => 'required',
            'bukti.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $file = null;
        
        try {
            DB::beginTransaction();
            $pengeluaran = Pengeluaran::find($request->id);
            $account = Account::where('id', $pengeluaran->account_id)->first();

            if ($request->imagecheck) {
                if ($request->hasfile('bukti')) {
                    foreach (json_decode($pengeluaran->bukti) as $foto) {
                        $data[] = $foto;
                    }

                    foreach ($request->imagecheck as $check) {
                        $data = array_diff($data, array($check));
                        File::delete('uploads/pengeluaran/'.$check);                
                    }

                    foreach ($request->file('bukti') as $file) {
                        $namafile = time() . '-' . $file->getClientOriginalName();
                        $file->move('uploads/pengeluaran', $namafile);
                        $data[] = $namafile; 
                    }
                } else {
                    foreach (json_decode($pengeluaran->bukti) as $foto) {
                        $data[] = $foto;
                    }
        
                    foreach ($request->imagecheck as $check) {
                        $data = array_diff($data, array($check));
                        File::delete('uploads/pengeluaran/'.$check);
                    }
                }

                $upload = json_encode($data);
            }

            if (($request->hasfile('bukti')) && (empty($request->imagecheck))) {
                foreach (json_decode($pengeluaran->bukti) as $foto) {
                    $data[] = $foto;
                }
        
                foreach ($request->file('bukti') as $file) {
                    $namafile = time() . '-' . $file->getClientOriginalName();
                    $file->move('uploads/pengeluaran', $namafile);
                    $data[] = $namafile;
                } 

                $upload = json_encode($data);
            }

            if ($account->id <> $request->account) {
                $accountbaru = Account::where('id', $request->account)->first();
                $account->saldo = $account->saldo + str_replace(',', '', $pengeluaran->jumlah);
                $accountbaru->saldo = $accountbaru->saldo - str_replace(',', '', $request->jumlah);
                $accountbaru->save();
            } else {
                $account->saldo = ($account->saldo + str_replace(',', '', $pengeluaran->jumlah)) - str_replace(',', '', $request->jumlah);
            }

            $account->save();
            
            $pengeluaran->user_id = $request->admin;
            $pengeluaran->tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
            $pengeluaran->account_id = $request->account;
            $pengeluaran->kategori_id = $request->kategori;
            $pengeluaran->aktivitas = $request->aktivitas;
            $pengeluaran->jumlah = str_replace( ',', '', $request->jumlah);
            $pengeluaran->bukti = empty($upload) ? $pengeluaran->bukti : $upload;
            $pengeluaran->catatan = empty($request->catatan) ? '' : $request->catatan;

            $pengeluaran->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        return redirect()->route('pengeluaran.index')->withStatus('Pengeluaran berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengeluaran $pengeluaran)
    {
        try {
            DB::beginTransaction();
            if ($pengeluaran->bukti) {
                foreach (json_decode($pengeluaran->bukti) as $foto) {
                    File::delete('uploads/pengeluaran/'.$foto);
                }
            }

            $account = Account::where('id', $pengeluaran->account_id)->first();
            $account->saldo = $account->saldo + str_replace(',', '', $pengeluaran->jumlah);
            $account->save();
            $pengeluaran->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        return redirect()->route('pengeluaran.index')->withStatus('Pengeluaran berhasil dihapus');
    }

    public function dataTable()
    { 
        $model = Pengeluaran::with('account')->with('kategori');

        $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
        $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
 
        if ($start_date && $end_date)
        {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            
            $model->whereRaw("date(pengeluaran.tanggal) >= '" . $start_date . "' AND date(pengeluaran.tanggal) <= '" . $end_date . "'");
        }
        else 
        {
            $model->whereMonth('tanggal', Carbon::now()->month)
                  ->whereYear('tanggal', Carbon::now()->year);
        }

        return DataTables::of($model)
            ->addColumn('action', function ($model) {
                return '
                <a href="'. route ('pengeluaran.show', $model->id) .'" class="btn btn-success btn-xs"><i class="la flaticon-search-2"></i></a>
                <a href="'. route ('pengeluaran.edit', $model->id) .'" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a>  
                <button class="btn btn-xs btn-danger btn-delete" data-remote="/pengeluaran/' . $model->id . '"><i class="fas fa-trash"></i></button>';
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
