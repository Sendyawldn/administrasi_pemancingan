@extends('layouts.app')

@section('content')
<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white dark:bg-zinc-900 shadow-lg rounded-xl p-8 ring-1 ring-zinc-200 dark:ring-white/10">
            <div class="text-center mb-8">
                <i class="fas fa-weight-scale text-4xl text-green-500 mb-3"></i>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                    Selesai Sesi Pemancingan
                </h2>
                <p class="mt-1 text-md font-semibold text-zinc-500 dark:text-zinc-400">
                    {{ $transaksi->nama_pelanggan }} - Kolam {{ $transaksi->meja->nama_meja }}
                </p>
            </div>

            <form action="{{ route('transaksi.selesai.proses', $transaksi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Info Sesi -->
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <h3 class="font-medium text-zinc-900 dark:text-white">Detail Sesi</h3>
                        <div class="mt-2 space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                            <p>🕒 Mulai: {{ $transaksi->waktu_mulai->format('H:i') }}</p>
                            <p>⏱️ Durasi: {{ $transaksi->durasi }} jam</p>
                            <p>💰 Harga Paket: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Input Hasil Tangkapan -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="jumlah_ikan_kecil" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Ikan Kecil (ekor)
                            </label>
                            <div class="mt-1">
                                <input type="number" name="jumlah_ikan_kecil" id="jumlah_ikan_kecil" 
                                       value="0" min="0" required class="form-input">
                            </div>
                            <p class="mt-1 text-xs text-zinc-500">Rp 5.000/ekor</p>
                        </div>

                        <div>
                            <label for="berat_ikan_babon" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Ikan Babon (kg)
                            </label>
                            <div class="mt-1">
                                <input type="number" name="berat_ikan_babon" id="berat_ikan_babon" 
                                       value="0" min="0" step="0.1" required class="form-input">
                            </div>
                            <p class="mt-1 text-xs text-zinc-500">Rp 25.000/kg</p>
                        </div>
                    </div>

                    <!-- Total Biaya Preview -->
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <h3 class="font-medium text-green-800 dark:text-green-300">Total Biaya</h3>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="total_preview">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                            (Paket + Ikan Kecil + Ikan Babon)
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-8">
                    <a href="{{ route('dashboard') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary bg-green-600 hover:bg-green-700 focus:ring-green-500">
                        Selesaikan & Bayar
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
    const ikanKecilInput = document.getElementById('jumlah_ikan_kecil');
    const ikanBabonInput = document.getElementById('berat_ikan_babon');
    const totalPreview = document.getElementById('total_preview');
    
    const hargaPaket = {{ $transaksi->total_harga }};
    const hargaIkanKecil = 5000;
    const hargaIkanBabon = 25000;

    function calculateTotal() {
        const ikanKecil = parseInt(ikanKecilInput.value) || 0;
        const ikanBabon = parseFloat(ikanBabonInput.value) || 0;
        
        const totalIkanKecil = ikanKecil * hargaIkanKecil;
        const totalIkanBabon = ikanBabon * hargaIkanBabon;
        const total = hargaPaket + totalIkanKecil + totalIkanBabon;
        
        totalPreview.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    ikanKecilInput.addEventListener('input', calculateTotal);
    ikanBabonInput.addEventListener('input', calculateTotal);
});
</script>
@endpush