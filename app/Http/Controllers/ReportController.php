<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan transaksi dengan filter.
     */
    public function transactions(Request $request)
    {
        // Ambil data untuk filter dropdown
        $users = User::role('Kasir')->orWhere('id', 1)->get(); // Ambil kasir atau admin
        $cabangs = Cabang::all();

        // Mulai query builder
        $query = Transaction::with(['user', 'customer', 'cabang']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Filter berdasarkan kasir (user)
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan cabang
        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }
        
        // Ambil data sebelum di-paginate untuk summary
        $summaryQuery = clone $query;
        $totalRevenue = $summaryQuery->sum('total_amount');
        $totalTransactions = $summaryQuery->count();

        // Ambil hasil akhir dengan paginasi
        $transactions = $query->latest()->paginate(20)->withQueryString();

        return view('reports.transactions', compact(
            'transactions', 
            'users', 
            'cabangs',
            'totalRevenue',
            'totalTransactions'
        ));
    }
}
