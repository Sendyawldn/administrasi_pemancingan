<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->integer('jumlah_ikan_kecil')->default(0)->after('total_harga');
            $table->decimal('berat_ikan_babon', 8, 2)->default(0)->after('jumlah_ikan_kecil');
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['jumlah_ikan_kecil', 'berat_ikan_babon']);
        });
    }
};