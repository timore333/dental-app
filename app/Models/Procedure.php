<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procedure extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function billItems(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    public function companyPriceLists(): HasMany
    {
        return $this->hasMany(InsuranceCompanyPriceList::class);
    }

    public function visitProcedures(): HasMany
    {
        return $this->hasMany(VisitProcedure::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ==================== METHODS ====================

    /**
     * Get price for a procedure based on patient type
     * If patient is insurance, get price from insurance company price list
     * If patient is cash, get default price from procedure
     */
    public function getPriceForPatient($patient): ?float
    {
        if ($patient->isInsurance() && $patient->insuranceCompany) {
            return $patient->insuranceCompany->getPriceForProcedure($this->id);
        }

        // For cash patients, return the default procedure price (can be null)
        return $this->default_price;
    }

    /**
     * Check if this procedure has a price for the given patient
     */
    public function hasPriceForPatient($patient): bool
    {
        return $this->getPriceForPatient($patient) !== null;
    }
}
