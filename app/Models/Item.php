<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items'; // Specify the table name if it doesn't follow Laravel's naming conventions

    protected $fillable = [
        'nama',
        'jumlah',
        'foto',
    ];
}
