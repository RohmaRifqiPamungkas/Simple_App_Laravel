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
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'description' => 'required|nullable|string',
            'rate' => 'required|numeric',
            'date_paid' => 'required|nullable|date',
            'category' => 'required|string|in:Income,Expense',
            'details' => 'required|array',
            'details.*.name' => 'required|string',
            'details.*.amount' => 'required|numeric',
        ]);

        $transaction = Transaction::create($request->only(['code', 'description', 'rate', 'date_paid', 'category']));

        foreach ($request->details as $detail) {
            $transaction->details()->create($detail);
        }

        return redirect()->route('transactions.all')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the transaction recap.
     *
     * @return \Illuminate\Http\Response
     */
    public function recap()
    {
        return view('transactions.recap');
    }
}