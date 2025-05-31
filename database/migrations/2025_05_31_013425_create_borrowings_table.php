<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained('stock_items')->onDelete('cascade'); // barang yang dipinjam
            $table->unsignedInteger('jumlah');       // jumlah barang yang dipinjam
            $table->string('borrower_name');         // nama peminjam
            $table->timestamps();                    // created_at untuk tanggal pinjam
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
}
