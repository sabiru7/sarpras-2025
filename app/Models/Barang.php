<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama',
        'stok',
        'gambar', // Include gambar in fillable attributes
    ];

    public function isAvailable()
    {
        return $this->stok > 0;
    }
}
