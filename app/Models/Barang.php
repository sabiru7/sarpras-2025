<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

   use HasFactory;

    protected $fillable = ['nama', 'jumlah', 'foto'];
    public function isAvailable()
    {
        return $this->stok > 0;
    }
}
