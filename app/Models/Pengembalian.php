<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalians';

    protected $fillable = [
        'borrowing_id',          // harus sama dengan nama kolom di database
        'tanggal_pengembalian',
        'keterangan',            // kalau pakai keterangan, jangan lupa ditambahkan
    ];

    /**
     * Relasi ke model Borrowing (Peminjaman)
     */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class, 'borrowing_id');  // pastikan nama model dan foreign key benar
    }
}
