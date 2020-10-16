<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHutangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hutang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->integer('pegawai_id')->unsigned();
            $table->integer('kategori_id')->unsigned();
            $table->date('tanggal');
            $table->string('aktivitas', 100);
            $table->integer('jumlah');
            $table->mediumText('bukti')->nullable();
            $table->text('catatan');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hutang');
    }
}
