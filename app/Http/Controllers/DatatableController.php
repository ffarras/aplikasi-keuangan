<?php

namespace App\Http\Controllers;

use App\Hutang;
use App\Pegawai;
use App\Pemasukan;
use App\Pengeluaran;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;

class DatatableController extends Controller
{
    public function pemasukanTable()
    {
        $model = Pemasukan::with('account');

        $start_date = (!empty(filter_input(INPUT_GET, 'start_date'))) ? (filter_input(INPUT_GET, 'start_date')) : ('');
        $end_date = (!empty(filter_input(INPUT_GET, 'end_date'))) ? (filter_input(INPUT_GET, 'end_date')) : ('');

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
                <a href="' . route('pemasukan.show', $model->id) . '" class="btn btn-success btn-xs"><i class="la flaticon-search-2"></i></a>
                <a href="' . route('pemasukan.edit', $model->id) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a>  
                <button class="btn btn-xs btn-danger btn-delete" data-remote="/pemasukan/' . $model->id . '"><i class="fas fa-trash"></i></button>';
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function pengeluaranTable()
    {
        $model = Pengeluaran::with('account')->with('kategori');

        $start_date = (!empty(filter_input(INPUT_GET, 'start_date'))) ? (filter_input(INPUT_GET, 'start_date')) : ('');
        $end_date = (!empty(filter_input(INPUT_GET, 'end_date'))) ? (filter_input(INPUT_GET, 'end_date')) : ('');

        if ($start_date && $end_date) {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));

            $model->whereRaw("date(pengeluaran.tanggal) >= '" . $start_date . "' AND date(pengeluaran.tanggal) <= '" . $end_date . "'");
        } else {
            $model->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year);
        }

        return DataTables::of($model)
            ->addColumn('action', function ($model) {
                return '
                <a href="' . route('pengeluaran.show', $model->id) . '" class="btn btn-success btn-xs"><i class="la flaticon-search-2"></i></a>
                <a href="' . route('pengeluaran.edit', $model->id) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a>  
                <button class="btn btn-xs btn-danger btn-delete" data-remote="/pengeluaran/' . $model->id . '"><i class="fas fa-trash"></i></button>';
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function hutangTable()
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

    public function pegawaiTable()
    {
        $model = Pegawai::query();
        return DataTables::of($model)
            ->addColumn('action', function ($model) {
                return '
                <a href="' . route('pegawai.edit', $model->id) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a>  
                <button class="btn btn-xs btn-danger btn-delete" data-remote="/pegawai/' . $model->id . '"><i class="fas fa-trash"></i></button>';
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
