@extends('layouts.app')

@section('content')
<div>
    {{-- Header --}}
    <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4 mb-6">
        <h2 class="text-2xl font-bold text-zinc-800 dark:text-white">Dashboard</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Combro Fishing Management</p>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
        <div class="bg-white dark:bg-zinc-900/50 rounded-lg shadow-md p-5 border-t-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-3xl font-semibold text-zinc-900 dark:text-white">
                        {{ $mejas->where('status', 'tersedia')->count() }}
                    </h3>
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Kolam Tersedia</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400">
                    <i class="fas fa-check text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900/50 rounded-lg shadow-md p-5 border-t-4 border-orange-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-3xl font-semibold text-zinc-900 dark:text-white">
                        {{ $mejas->where('status', 'digunakan')->count() }}
                    </h3>
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Kolam Digunakan</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400">
                    <i class="fas fa-fish text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900/50 rounded-lg shadow-md p-5 border-t-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-3xl font-semibold text-zinc-900 dark:text-white">
                        {{ $transaksis_berjalan->count() }}
                    </h3>
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Sesi Berjalan</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-play text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLAM PEMANCINGAN (GABUNGAN) --}}
    @if($mejas->count() > 0)
    <div>
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white mb-4">
            🎣 Kolam Pemancingan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($mejas as $meja)
            @php
                // Cari transaksi berjalan untuk kolam ini
                $transaksi_aktif = $transaksis_berjalan->firstWhere('meja_id', $meja->id);
            @endphp
            
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 
                        {{ $transaksi_aktif ? 'ring-2 ring-yellow-500' : '' }}">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-semibold text-zinc-900 dark:text-white">
                        {{ $meja->nama_meja }}
                    </h3>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $meja->status === 'tersedia' 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' 
                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                        {{ $meja->status === 'tersedia' ? 'Tersedia' : 'Digunakan' }}
                    </span>
                </div>
                
                {{-- TAMPILKAN INFO TRANSAKSI JIKA ADA --}}
                @if($transaksi_aktif)
                <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                    <div class="space-y-2">
                        {{-- Info Pemancing --}}
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-yellow-800 dark:text-yellow-300 text-sm">
                                    {{ $transaksi_aktif->nama_pelanggan }}
                                </h4>
                                <div class="mt-1 space-y-1 text-xs text-yellow-600 dark:text-yellow-400">
                                    <p>⏱️ {{ $transaksi_aktif->durasi }} jam</p>
                                    <p>🕒 Mulai: {{ $transaksi_aktif->waktu_mulai->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="grid grid-cols-2 gap-2 mt-3">
                            <a href="{{ route('transaksi.selesai.form', $transaksi_aktif->id) }}" 
                               class="bg-green-600 hover:bg-green-700 text-white text-xs px-2 py-2 rounded transition text-center">
                                Selesai
                            </a>
                            <a href="{{ route('pesanan.create', $transaksi_aktif->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-2 py-2 rounded transition text-center">
                                Pesan
                            </a>
                        </div>
                        <form action="{{ route('transaksi.batal', $transaksi_aktif->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-2 rounded transition" 
                                    onclick="return confirm('Batalkan sesi pemancingan?')">
                                Batal Sesi
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                    Rp {{ number_format($meja->tarif_per_jam, 0, ',', '.') }}/jam
                </p>

                @if($meja->status === 'tersedia')
                <a href="{{ route('transaksi.create', $meja->id) }}" 
                   class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-fish"></i>
                    Mulai Sesi
                </a>
                @else
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-zinc-400 bg-zinc-100 dark:bg-zinc-700 rounded-lg cursor-not-allowed" disabled>
                    <i class="fas fa-pause"></i>
                    Sedang Digunakan
                </button>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="text-center py-16">
        <i class="fas fa-water text-6xl text-zinc-300 dark:text-zinc-600 mb-4"></i>
        <h3 class="text-xl font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Belum Ada Kolam</h3>
        <p class="text-zinc-500 dark:text-zinc-400 mb-4">
            Silakan tambahkan kolam untuk mulai mengelola pemancingan.
        </p>
        <a href="{{ route('meja.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus"></i>
            Tambah Kolam
        </a>
    </div>
    @endif
</div>

{{-- Notifikasi Popup --}}
<div x-data="{ show: false, type: '', message: '' }"
     x-init="
         @if(session('success'))
             show = true; type = 'success'; message = '{{ session('success') }}';
         @elseif(session('error'))
             show = true; type = 'error'; message = '{{ session('error') }}';
         @endif
     "
     x-show="show"
     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4"
     style="display: none;">

    <div @click.away="show = false"
         x-show="show"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-xl text-center p-6">

        <div class="w-16 h-16 rounded-lg mx-auto flex items-center justify-center"
             :class="{ 'bg-green-100': type === 'success', 'bg-red-100': type === 'error' }">
            <i class="text-4xl" 
               :class="{ 'fa-solid fa-check text-green-600': type === 'success', 'fa-solid fa-times text-red-600': type === 'error' }"></i>
        </div>

        <h3 class="text-2xl font-bold mt-4 text-gray-800 dark:text-white"
            x-text="type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan'"></h3>
        
        <p class="text-gray-600 dark:text-gray-300 mt-2" x-text="message"></p>

        <button @click="show = false"
                class="mt-6 w-full px-4 py-2 rounded-lg text-white font-semibold"
                :class="{ 'bg-green-600 hover:bg-green-700': type === 'success', 'bg-red-600 hover:bg-red-700': type === 'error' }">
            Tutup
        </button>
    </div>
</div>
@endsection