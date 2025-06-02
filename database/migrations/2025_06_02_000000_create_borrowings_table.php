<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_item_id');
            $table->foreign('stock_item_id')->references('id')->on('stock_items')->onDelete('cascade');

            $table->integer('jumlah');
            $table->string('borrower_name');
            $table->text('alasan');
            $table->date('borrow_date')->default(DB::raw('CURRENT_DATE')); // default hari ini
            $table->date('return_date')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dipinjam', 'kembali'])->default('menunggu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
