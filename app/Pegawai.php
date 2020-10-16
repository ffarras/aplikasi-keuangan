<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
	protected $table = 'pegawai';

    protected $fillable = [
        'id',
        'nama',
        'jabatan',
        'created_at',
    ];

    protected $hidden = [
    	'updated_at',
    	'deleted_at',
    ];
}
