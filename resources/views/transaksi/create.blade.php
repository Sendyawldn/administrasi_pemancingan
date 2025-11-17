@extends('layouts.app')

@section('content')
<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white dark:bg-zinc-900 shadow-lg rounded-xl p-8 ring-1 ring-zinc-200 dark:ring-white/10">
            <div class="text-center mb-8">
                <i class="fas fa-fish text-4xl text-blue-500 mb-3"></i>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                    Mulai Sesi Pemancingan
                </h2>
                <p class="mt-1 text-md font-semibold text-zinc-500 dark:text-zinc-400">
                    Kolam {{ $meja->nama_meja }}
                </p>
            </div>

            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="meja_id" value="{{ $meja->id }}">

                <div class="space-y-6">
                    <div>
                        <label for="nama_pelanggan" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Nama Pemancing
                        </label>
                        <div class="mt-1">
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" required
                                   placeholder="Masukkan nama pemancing"
                                   class="form-input">
                        </div>
                    </div>

                    <div>
                        <label for="durasi" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Pilih Paket Sesi
                        </label>
                        <div class="mt-1">
                            <select name="durasi" id="durasi" required class="form-input">
                                <option value="">-- Pilih Paket --</option>
                                <option value="4">🎣 Sesi Pagi (4 Jam) - Rp {{ number_format(4 * $meja->tarif_per_jam, 0, ',', '.') }}</option>
                                <option value="3">🎣 Sesi Siang (3 Jam) - Rp {{ number_format(3 * $meja->tarif_per_jam, 0, ',', '.') }}</option>
                                <option value="5">🎣 Sesi Malam (5 Jam) - Rp {{ number_format(5 * $meja->tarif_per_jam, 0, ',', '.') }}</option>
                            </select>
                        </div>
                        <p class="mt-1 text-xs text-zinc-500">Tarif dasar: Rp {{ number_format($meja->tarif_per_jam, 0, ',', '.') }}/jam</p>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-8">
                    <a href="{{ route('dashboard') }}" class="btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn-primary bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                        Mulai Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const btn  = document.querySelector('button[type="submit"]');

    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...`;
    });
});
</script>
@endpush