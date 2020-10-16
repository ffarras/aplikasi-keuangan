<?php

namespace App\Http\Controllers;

use App\Pemasukan;
use App\Kategori;
use App\User;
use App\Account;
use App\Exports\PemasukanExport;
use Illuminate\Http\Request;
use File;
use DataTables;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class PemasukanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pemasukan = Pemasukan::get();

        return view('pemasukan.index', compact('pemasukan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account = Account::all();
        return view('pemasukan.create', compact('account'));
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
            'aktivitas' => 'required|regex:/^[A-Za-z&-\/ ]+$/',
            'jumlah' => 'required',
            'bukti.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();
            if ($request->hasfile('bukti')) {
                foreach ($request->file('bukti') as $file) {
                    $namafile = time() . '-' . $file->getClientOriginalName();
                    $file->move('uploads/pemasukan', $namafile);
                    $data[] = $namafile;
                }
    
                $upload = json_encode($data);
            } 
    
            $request->tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
    
            Pemasukan::create([
                'user_id' => $request->admin,
                'tanggal' => $request->tanggal,
                'account_id' => $request->account,
                'aktivitas' => $request->aktivitas,
                'jumlah'=> str_replace( ',', '', $request->jumlah),
                'bukti' => empty($upload) ? '' : $upload,
                'catatan' => empty($request->catatan) ? '' : $request->catatan,
            ]);

            $account = Account::where('id', $request->account)->first();
            $account->saldo = $account->saldo + str_replace(',', '', $request->jumlah);
            $account->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        return redirect('/pemasukan')->withStatus('Pemasukan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pemasukan  $pemasukan
     * @return \Illuminate\Http\Response
     */
    public function show(Pemasukan $pemasukan)
    {
        return view('pemasukan.show', compact('pemasukan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pemasukan  $pemasukan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pemasukan $pemasukan)
    {
        $account = Account::all();
        return view('pemasukan.edit', compact('pemasukan', 'account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pemasukan  $pemasukan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pemasukan $pemasukan)
    {
        $request->validate([
            'admin' => 'required',
            'tanggal' => 'required',
            'account' => 'required',
            'aktivitas' => 'required|regex:/^[A-Za-z&-\/ ]+$/',
            'jumlah' => 'required',
            'bukti.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $file = null;

        try {
            DB::beginTransaction();
            $pemasukan = Pemasukan::find($request->id);
            $account = Account::where('id', $pemasukan->account_id)->first();

            $pemasukan->user_id = $request->admin;
            $pemasukan->tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
            $pemasukan->aktivitas = $request->aktivitas;
            if ($request->imagecheck) {
                if ($request->hasfile('bukti')) {
                    foreach (json_decode($pemasukan->bukti) as $foto) {
                        $data[] = $foto;
                    }

                    foreach ($request->imagecheck as $check) {
                        $data = array_diff($data, array($check));
                        File::delete('uploads/pemasukan/'.$check);
                    }

                    foreach ($request->file('bukti') as $file) {
                        $namafile = time() . '-' . $file->getClientOriginalName();
                        $file->move('uploads/pemasukan', $namafile);
                        $data[] = $namafile;
                    }
                } else {
                    foreach (json_decode($pemasukan->bukti) as $foto) {
                        $data[] = $foto;
                    }

                    foreach ($request->imagecheck as $check) {
                        $data = array_diff($data, array($check));
                        File::delete('uploads/pemasukan/'.$check);
                    }
                }

                $upload = json_encode($data);
            }

            if (($request->hasfile('bukti')) && (empty($request->imagecheck))) {
                if ($pemasukan->bukti) {
                    foreach (json_decode($pemasukan->bukti) as $foto) {
                        $data[] = $foto;
                    }
                }

                foreach ($request->file('bukti') as $file) {
                    $namafile = time() . '-' . $file->getClientOriginalName();
                    $file->move('uploads/pemasukan', $namafile);
                    $data[] = $namafile;
                }

                $upload = json_encode($data);
            }
            $pemasukan->bukti = empty($upload) ? $pemasukan->bukti : $upload;
            $pemasukan->catatan = empty($request->catatan) ? $pemasukan->catatan : $request->catatan;
            
            if ($account->id <> $request->account) {
                $accountbaru = Account::where('id', $request->account)->first();
                $account->saldo = $account->saldo - str_replace(',', '', $pemasukan->jumlah);
                $accountbaru->saldo = $accountbaru->saldo + str_replace(',', '', $request->jumlah);
                $accountbaru->save();
            } else {
                $account->saldo = ($account->saldo - str_replace(',', '', $pemasukan->jumlah)) + str_replace(',', '', $request->jumlah);
            }

            $account->save();

            $pemasukan->account_id = $request->account;
            $pemasukan->jumlah = str_replace(',', '', $request->jumlah);

            $pemasukan->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        
        return redirect()->route('pemasukan.index')->withStatus('Pemasukan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pemasukan  $pemasukan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pemasukan $pemasukan)
    {
        try {
            DB::beginTransaction();
            if ($pemasukan->bukti) {
                foreach (json_decode($pemasukan->bukti) as $foto) {
                    File::delete('uploads/pemasukan/'.$foto);
                }
            }
            $account = Account::where('id', $pemasukan->account_id)->first();
            $account->saldo = $account->saldo - str_replace(',', '', $pemasukan->jumlah);

            $account->save();
            $pemasukan->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        
        return redirect()->route('pemasukan.index')->withStatus('Pemasukan berhasil dihapus');
    }

    public function dataTable()
    {
        $model = Pemasukan::with('account');

        $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
        $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
 
        if ($start_date && $end_date) {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            
            $model->whereRaw("date(pemasukan.tanggal) >= '" . $start_date . "' AND date(pemasukan.tanggal) <= '" . $end_date . "'");
        } else {
            $model->whereMonth('tanggal', Carbon::now()->month)
                  ->whereYear('tanggal', Carbon::now()->year);
        }

        return DataTables::of($model)
            ->addColumn('action', function ($model) {
                return '
                <a href="'. route ('pemasukan.show', $model->id) .'" class="btn btn-success btn-xs"><i class="la flaticon-search-2"></i></a>
                <a href="'. route ('pemasukan.edit', $model->id) .'" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a>  
                <button class="btn btn-xs btn-danger btn-delete" data-remote="/pemasukan/' . $model->id . '"><i class="fas fa-trash"></i></button>';
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
