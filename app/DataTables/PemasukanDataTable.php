<?php

namespace App\DataTables;

use App\Pemasukan;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use Carbon\Carbon;

class PemasukanDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
        $end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
 
        if ($start_date && $end_date)
        {
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            
            $query->whereRaw("date(pemasukan.tanggal) >= '" . $start_date . "' AND date(pemasukan.tanggal) <= '" . $end_date . "'");
        }
        else 
        {
            $month = Carbon::now()->month;
            $query->whereYear('tanggal', Carbon::now()->year);
        }

        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($query) {
                return '
                <a href="'. route ('pemasukan.show', $query->id) .'" class="btn btn-success btn-xs"><i class="la flaticon-search-2"></i></a>
                <a href="'. route ('pemasukan.edit', $query->id) .'" class="btn btn-warning btn-xs"><i class="fas fa-pen"></i></a>  
                <button class="btn btn-xs btn-danger btn-delete" data-remote="/pemasukan' . $query->id . '"><i class="fas fa-trash"></i></button>
                            ';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Pemasukan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Pemasukan $model)
    {
        $pemasukan = Pemasukan::with('account')->with('kategori')->select();

        return $pemasukan->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('pemasukan-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'DT_RowIndex' => ['data' => 'id', 'orderable'=> false, 'searchable' => false],
            'tanggal' => ['data' => 'tanggal', 'name' => 'pemasukan.tanggal', 'class' => 'text-center', 'width' => '80px'],
            'account' => ['data' => 'account.nama', 'name' => 'account.nama'],
            'aktivitas' => ['data' => 'aktivitas'],
            'jumlah' => ['data' => 'jumlah'],
            'action' => ['data' => 'action'],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Pemasukan_' . date('YmdHis');
    }
}
