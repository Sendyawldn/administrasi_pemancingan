<?php
// app/Http/Controllers/TransaksiController.php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Meja;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function create($meja_id)
    {
        $meja = Meja::findOrFail($meja_id);
        if ($meja->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        return view('transaksi.create', compact('meja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'meja_id' => 'required|exists:meja,id',
            'nama_pelanggan' => 'required|string|max:255',
            'durasi' => 'required|integer|min:1',
        ]);

        $meja = Meja::findOrFail($request->meja_id);
        $durasi = (int) $request->durasi;
        
        // Hitung harga paket saja (ikan nanti di akhir)
        $harga_paket = $meja->tarif_per_jam * $durasi;

        $transaksi = Auth::user()->transaksis()->create([
            'meja_id' => $meja->id,
            'nama_pelanggan' => $request->nama_pelanggan,
            'durasi' => $durasi,
            'total_harga' => $harga_paket,
            'waktu_mulai' => now(),
            'waktu_selesai' => now()->addHours($durasi),
            'jumlah_ikan_kecil' => 0,
            'berat_ikan_babon' => 0,
        ]);

        $meja->update(['status' => 'digunakan']);

        return redirect()->route('dashboard')
            ->with('success', 'Sesi pemancingan berhasil dimulai.');
    }

    public function selesaiForm($id)
    {
        $transaksi = Transaksi::with('meja')->findOrFail($id);
        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        return view('transaksi.selesai', compact('transaksi'));
    }

    public function selesaiProses(Request $request, $id)
    {
        $transaksi = Transaksi::with('meja')->findOrFail($id);
        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'jumlah_ikan_kecil' => 'required|integer|min:0',
            'berat_ikan_babon' => 'required|numeric|min:0',
        ]);

        // Hitung total dengan ikan
        $harga_paket = $transaksi->total_harga;
        $harga_ikan_kecil = $request->jumlah_ikan_kecil * 5000;
        $harga_ikan_babon = $request->berat_ikan_babon * 25000;
        $total_harga = $harga_paket + $harga_ikan_kecil + $harga_ikan_babon;

        $transaksi->update([
            'jumlah_ikan_kecil' => $request->jumlah_ikan_kecil,
            'berat_ikan_babon' => $request->berat_ikan_babon,
            'total_harga' => $total_harga,
            'waktu_selesai' => now(),
        ]);

        $transaksi->meja->update(['status' => 'tersedia']);

        return redirect()->route('dashboard')
            ->with('success', 'Sesi selesai. Total: Rp '.number_format($total_harga, 0, ',', '.'));
    }

    public function batal($id)
    {
        $transaksi = Transaksi::with('meja')->findOrFail($id);
        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $transaksi->meja->update(['status' => 'tersedia']);
        $transaksi->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Transaksi dibatalkan.');
    }

    public function histori()
    {
        $transaksis = Auth::user()->transaksis()
            ->with('meja')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('transaksi.histori', compact('transaksis'));
    }

    public function hapus(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        $transaksi->delete();
        return redirect()->route('transaksi.histori')->with('success', 'Transaksi dihapus.');
    }

    public function laporan(Request $request)
    {
        $query = Auth::user()->transaksis()->with('meja');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_selesai)->endOfDay()
            ]);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $transaksis->sum('total_harga');
        $mejas = Auth::user()->mejas()->get();

        return view('transaksi.laporan', compact('transaksis', 'totalPendapatan', 'mejas'));
    }

    public function cetakLaporan(Request $request)
    {
        $query = Auth::user()->transaksis()->with('meja');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_selesai)->endOfDay()
            ]);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $transaksis->sum('total_harga');

        $pdf = PDF::loadView('transaksi.laporan-pdf', compact('transaksis', 'totalPendapatan'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-pemancingan.pdf');
    }
}