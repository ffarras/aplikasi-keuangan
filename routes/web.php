<?php

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::resource('pegawai', 'PegawaiController', ['except' => ['show']]);
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::resource('account', 'AccountController');
	Route::resource('kategori', 'KategoriController', ['except' => ['show']]);
	Route::resource('pemasukan', 'PemasukanController');
	Route::resource('pengeluaran', 'PengeluaranController');
	Route::resource('hutang', 'HutangController');
	Route::get('laporan', 'LaporanController@index')->name('laporan');
	Route::get('/startdate/{startdate}/enddate/{enddate}/jenis/{jenis}', ['as' => 'laporanpdf', 'uses' => 'LaporanController@laporanPDF']);
	Route::get('/table/pegawai', 'PegawaiController@dataTable')->name('table.pegawai');
	Route::get('/table/kategori', 'KategoriController@dataTable')->name('table.kategori');
	Route::get('/table/pemasukan', 'PemasukanController@dataTable')->name('table.pemasukan');
	Route::get('/table/pengeluaran', 'PengeluaranController@dataTable')->name('table.pengeluaran');
	Route::get('/table/hutang', 'HutangController@dataTable')->name('table.hutang');
	Route::get('/dashboard/countdata', 'DashboardController@dataHeader');
});

