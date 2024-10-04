<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mendapatkan semua transaksi dengan detailnya
        $transactions = Transaction::with('details')->get();
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Menampilkan form untuk membuat transaksi baru
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'code' => 'required',
            'description' => 'nullable|string',
            'rate' => 'required|numeric',
            'date_paid' => 'nullable|date',
            'category' => 'required|string|in:Income,Expense',
            'details' => 'required|array',
            'details.*.name' => 'required|string',
            'details.*.amount' => 'required|numeric',
        ]);

        // Membuat transaksi baru
        $transaction = Transaction::create($request->only(['code', 'description', 'rate', 'date_paid', 'category']));

        // Menyimpan detail transaksi
        foreach ($request->details as $detail) {
            $transaction->details()->create($detail);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    /**
     * Show the form for editing a transaction.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        // Menampilkan form untuk mengedit transaksi
        return view('transactions.edit', compact('transaction'));
    }

    /**
     * Update the specified transaction in the storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Validasi data yang diterima
        $request->validate([
            'code' => 'required',
            'description' => 'nullable|string',
            'rate' => 'required|numeric',
            'date_paid' => 'nullable|date',
            'category' => 'required|string|in:Income,Expense',
            'details' => 'required|array',
            'details.*.name' => 'required|string',
            'details.*.amount' => 'required|numeric',
        ]);

        // Memperbarui transaksi
        $transaction->update($request->only(['code', 'description', 'rate', 'date_paid', 'category']));

        // Hapus detail lama dan tambahkan detail yang baru
        $transaction->details()->delete();
        foreach ($request->details as $detail) {
            $transaction->details()->create($detail);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        // Hapus transaksi
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Display the transaction recap.
     *
     * @return \Illuminate\Http\Response
     */
    public function recap()
    {
        // Menampilkan rekap transaksi (logikanya perlu diimplementasikan)
        return view('transactions.recap');
    }
}