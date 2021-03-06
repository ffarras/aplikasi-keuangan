<?php

namespace App\Http\Controllers;

use App\Pegawai;
use Illuminate\Http\Request;
use DataTables;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pegawai = Pegawai::all();
        return view('pegawai.index', ['pegawai' => $pegawai]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|regex:/^[A-Za-z ]+$/',
            'jabatan' => 'required|regex:/^[A-Za-z&-\/ ]+$/',
        ]);

        Pegawai::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
        ]);

        return redirect('/pegawai')->withStatus('Pegawai berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|regex:/^[A-Za-z ]+$/',
            'jabatan' => 'required|regex:/^[A-Za-z&-\/ ]+$/',
        ]);

        $pegawai->update($request->all());

        return redirect()->route('pegawai.index')->withStatus('Pegawai berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();

        return redirect()->route('pegawai.index')->withStatus('Data berhasil dihapus');
    }
}
