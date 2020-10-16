<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'id',
        'kategori',
        'created_at',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function pemasukan()
    {
        return $this->hasMany('App\Pemasukan', 'kategori_id', 'id');
    }

    public function pengeluaran()
    {
        return $this->hasMany('App\Pengeluaran', 'kategori_id', 'id');
    }

}
