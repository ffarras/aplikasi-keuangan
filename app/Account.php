<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'account';

    protected $fillable = [
        'id',
        'nama',
        'pemilik',
        'nomor_acc',
        'saldo',
        'created_at',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];
}
