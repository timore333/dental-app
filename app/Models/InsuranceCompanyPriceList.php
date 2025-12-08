<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceCompanyPriceList extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_company_id',
        'procedure_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * The insurance company
     */
    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    /**
     * The procedure
     */
    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }
}
