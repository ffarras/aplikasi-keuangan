<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pemasukan extends Model
{
    protected $table = 'pemasukan';

    protected $fillable = [
        'id',
        'user_id',
        'account_id',
        'tanggal',
        'aktivitas',
        'jumlah',
        'bukti',
        'catatan',
        'created_at',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function user() 
    {
        return $this->belongsTo('App\User', 'user_id', 'id');    
    }

    public function account()
    {
        return $this->belongsTo('App\Account', 'account_id', 'id');
    }

    public function getTanggalAttribute($tanggal)
    {
        return Carbon::parse($tanggal)->format('d-m-Y');
    }

    public function getJumlahAttribute($jumlah)
    {
        $jumlah = number_format($jumlah, 0, '', ',');

        return $jumlah;
    }
}
