<?php

namespace App\Http\Controllers;

use App\Hutang;
use App\Pemasukan;
use App\Pengeluaran;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dataHeader(Request $request)
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        $pemasukan = Pemasukan::whereMonth('tanggal', Carbon::now()->month)
                     ->count();
        $totalpemasukan = Pemasukan::whereMonth('tanggal', Carbon::now()->month)
                     ->sum('jumlah');
        $totalpemasukan = 'Rp ' . number_format($totalpemasukan, 0, '', ',');
        $pemasukan7day = DB::select(
            'SELECT tanggal AS tanggal, COUNT(id) AS qty
            FROM pemasukan
            GROUP BY tanggal
            ORDER BY tanggal DESC
            
            LIMIT 7;'
        );

        $pengeluaran = Pengeluaran::whereMonth('tanggal', Carbon::now()->month)
                        ->count();
        $totalpengeluaran = Pengeluaran::whereMonth('tanggal', Carbon::now()->month)
                        ->sum('jumlah');
        $totalpengeluaran = 'Rp ' . number_format($totalpengeluaran, 0, '', ',');
        $pengeluaran7day = DB::select(
            'SELECT tanggal AS tanggal, COUNT(id) AS qty
            FROM pengeluaran
            GROUP BY tanggal
            ORDER BY tanggal DESC
            
            LIMIT 7;'
        );

        $reimburse = Hutang::whereMonth('tanggal', Carbon::now()->month)
                    ->count();
        $totalreimburse = Hutang::whereMonth('tanggal', Carbon::now()->month)
                    ->sum('jumlah');
        $totalreimburse = 'Rp ' . number_format($totalreimburse, 0, '', ','); 
        $reimburse7day = DB::select(
            'SELECT tanggal AS tanggal, COUNT(id) AS qty
            FROM hutang
            GROUP BY tanggal
            ORDER BY tanggal DESC
            
            LIMIT 7;'
        );
        
        $result = array(
            'jmlPemasukan' => $pemasukan,
            'last7Pemasukan' => $pemasukan7day,
            'jmlPengeluaran' => $pengeluaran,
            'last7Pengeluaran' => $pengeluaran7day,
            'jmlReimburse' => $reimburse,
            'last7Reimburse' => $reimburse7day,
            'totalPemasukan' => $totalpemasukan,
            'totalPengeluaran' => $totalpengeluaran,
            'totalReimburse' => $totalreimburse,
        );

        return response()->json($result);
    }
}
