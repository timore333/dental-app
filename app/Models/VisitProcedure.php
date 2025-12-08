<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VisitProcedure extends Pivot
{
    use HasFactory;

    protected $table = 'visit_procedures';
    protected $fillable = ['visit_id', 'procedure_id', 'price_at_time', 'notes'];

    protected $casts = [
        'price_at_time' => 'decimal:2',
    ];
}
