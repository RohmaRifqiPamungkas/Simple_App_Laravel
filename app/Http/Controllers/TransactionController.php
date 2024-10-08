<?php

namespace App\Http\Controllers;

use App\Models\TransactionHeader;
use App\Models\TransactionDetail;
use App\Models\MsCategory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Mendapatkan semua transaksi dengan detailnya
        $transactions = TransactionHeader::with('details')->get();
        return view('transactions.index', compact('transactions'));
    }
    public function create()
    {
        // Ambil semua kategori untuk digunakan dalam form
        $categories = MsCategory::all();

        // Menampilkan form untuk membuat transaksi baru
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'code' => 'required',
            'description' => 'nullable|string',
            'rate' => 'required|numeric',
            'date_paid' => 'nullable|date',
            'details' => 'required|array',
            'details.*.name' => 'required|string',
            'details.*.amount' => 'required|numeric',
        ]);

        // Membuat atau mendapatkan kategori
        $categoryName = $request->input('category');
        $category = MsCategory::firstOrCreate(['name' => $categoryName]);

        // Membuat transaksi baru
        $transaction = TransactionHeader::create($request->only(['code', 'description', 'rate', 'date_paid']));

        // Menyimpan detail transaksi
        foreach ($request->details as $detail) {
            $transaction->details()->create([
                'transaction_category_id' => $category->id,
                'name' => $detail['name'],
                'value_idr' => $detail['amount'],
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    public function edit(TransactionHeader $transaction)
    {
        // Menampilkan form untuk mengedit transaksi
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, TransactionHeader $transaction)
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

        // Membuat atau mendapatkan kategori (tambahkan ini)
        $categoryName = $request->input('category');
        $category = MsCategory::firstOrCreate(['name' => $categoryName]);

        // Memperbarui transaksi
        $transaction->update($request->only(['code', 'description', 'rate', 'date_paid']));

        // Hapus detail lama dan tambahkan detail yang baru
        $transaction->details()->delete();
        foreach ($request->details as $detail) {
            $transaction->details()->create([
                'transaction_category_id' => $category->id,
                'name' => $detail['name'],
                'value_idr' => $detail['amount'],
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(TransactionHeader $transaction)
    {
        // Hapus transaksi
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    public function recap()
    {
        // Menampilkan rekap transaksi (logikanya perlu diimplementasikan)
        return view('transactions.recap');
    }
}
