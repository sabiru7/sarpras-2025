<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $fillable = [
        'loan_id',
        'return_date',
        'status',
        'quantity',
        'photo'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
