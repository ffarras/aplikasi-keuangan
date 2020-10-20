<?php

namespace App\Http\Controllers;

use App\Pemasukan;
use App\Pengeluaran;
use Illuminate\Http\Request;
use DB;
use PDF;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $startdate = $request->start_date;
        $enddate = $request->end_date;
        $jenis = $request->jenis_transaksi;

        $datalap = $this->datalap($startdate, $enddate, $jenis);

        $total = $datalap['total'];
        $union = $datalap['union'];

        // if (empty($request->start_date) && empty($request->end_date)) {
        //     switch ($request->jenis_transaksi) {
        //     case "pemasukan":
        //         $total = $pemasukan->sum('jumlah');
        //         $union = $pemasukan->orderBy('tanggal', 'asc')->get();
        //         break;
        //     case "pengeluaran":
        //         $total = 0-($pengeluaran->sum('jumlah'));
        //         $union = $pengeluaran->orderBy('tanggal', 'asc')->get();
        //         break;
        //     case "semua":
        //         $totalpem = $pemasukan->sum('jumlah');
        //         $totalpeng = $pengeluaran->sum('jumlah');
        //         $total = $totalpem-$totalpeng;
        //         $union = $pemasukan->union($pengeluaran)->orderBy('tanggal', 'asc')->get();
        //         break;
        //     default:
        //         $total = $union = '';
        //     }
        // }

        return view('laporan.index', [
            'total' => $total,
            'union' => $union
        ]);
    }

    public function laporanPDF($startdate, $enddate, $jenis)
    {
        $datalap = $this->datalap($startdate, $enddate, $jenis);

        $total = $datalap['total'];
        $union = $datalap['union'];

        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])
            ->loadView('laporan.laporanpdf', [
                'startdate' => $startdate,
                'enddate' => $enddate,
                'total' => $total,
                'union' => $union
            ]);

        return $pdf->stream('Laporan Keuangan Azura Labs.pdf');
    }

    public function datalap($startdate, $enddate, $jenis)
    {
        $total = $union = '';

        $startdate = date('Y-m-d', strtotime($startdate));
        $enddate = date('Y-m-d', strtotime($enddate));

        $fields = [
            'id',
            'account_id',
            'tanggal',
            'aktivitas',
            'jumlah'
        ];

        $pengeluaran = Pengeluaran::with('account')->select(array_merge($fields, [DB::raw('"Pengeluaran" as jenis')]));
        $pemasukan = Pemasukan::with('account')->select(array_merge($fields, [DB::raw('"Pemasukan" as jenis')]));

        switch ($jenis) {
            case "pemasukan":
                $pemasukan->whereRaw("date(pemasukan.tanggal) >= '" . $startdate . "' AND date(pemasukan.tanggal) <= '" . $enddate . "'");
                $total = $pemasukan->sum('jumlah');
                $union = $pemasukan->orderBy('tanggal', 'asc')->get();
                break;
            case "pengeluaran":
                $pengeluaran->whereRaw("date(pengeluaran.tanggal) >= '" . $startdate . "' AND date(pengeluaran.tanggal) <= '" . $enddate . "'");
                $total = 0 - ($pengeluaran->sum('jumlah'));
                $union = $pengeluaran->orderBy('tanggal', 'asc')->get();
                break;
            case "semua":
                $pengeluaran->whereRaw("date(pengeluaran.tanggal) >= '" . $startdate . "' AND date(pengeluaran.tanggal) <= '" . $enddate . "'");
                $pemasukan->whereRaw("date(pemasukan.tanggal) >= '" . $startdate . "' AND date(pemasukan.tanggal) <= '" . $enddate . "'");
                $totalpem = $pemasukan->sum('jumlah');
                $totalpeng = $pengeluaran->sum('jumlah');
                $total = $totalpem - $totalpeng;
                $union = $pemasukan->union($pengeluaran)->orderBy('tanggal', 'asc')->get();
        }

        return array('total' => $total, 'union' => $union);
    }
}
