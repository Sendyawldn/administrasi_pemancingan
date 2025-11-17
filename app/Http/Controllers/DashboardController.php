<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mejas = Auth::user()->mejas()->get();
        
        // Ambil transaksi berjalan (yang belum selesai)
        $transaksis_berjalan = Auth::user()->transaksis()
            ->with('meja')
            ->where(function($query) {
                $query->where('waktu_selesai', '>', now())
                      ->orWhereNull('waktu_selesai');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('mejas', 'transaksis_berjalan'));
    }
}