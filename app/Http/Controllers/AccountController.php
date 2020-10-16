<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account = Account::all();
        return view('account.index', ['account' => $account]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('account.create');
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
            'pemilik' => 'required|regex:/^[A-Za-z ]+$/',
            'nomor_acc' => 'required|regex:/^[0-9- ]+$/',
            'saldo' => 'required',
        ]);

        Account::create([
            'nama' => $request->nama,
            'pemilik' => $request->pemilik,
            'nomor_acc' => $request->nomor_acc,
            'saldo' => str_replace( ',', '', $request->saldo),
        ]);

        return redirect('/account')->withStatus('Account berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return view('account.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        return view('account.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'nama' => 'required|regex:/^[A-Za-z ]+$/',
            'pemilik' => 'required|regex:/^[A-Za-z ]+$/',
            'nomor_acc' => 'required|regex:/^[0-9- ]+$/',
            'saldo' => 'required',
        ]);

        $account = Account::find($request->id);
        $account->nama = $request->nama;
        $account->pemilik = $request->pemilik;
        $account->nomor_acc = $request->nomor_acc;
        $account->saldo = str_replace( ',', '', $request->saldo);

        $account->save();

        return redirect()->route('account.index')->withStatus('Account berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('account.index')->withStatus('Account berhasil dihapus');
    }
}
