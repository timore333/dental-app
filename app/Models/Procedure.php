<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procedure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'default_price',
        'category',
        'is_active',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * All bill items containing this procedure
     */
    public function billItems(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * All insurance company price lists for this procedure
     */
    public function companyPriceLists(): HasMany
    {
        return $this->hasMany(InsuranceCompanyPriceList::class);
    }

    /**
     * All visit procedures using this procedure
     */
    public function visitProcedures(): HasMany
    {
        return $this->hasMany(VisitProcedure::class);
    }

    // ==================== SCOPES ====================

    /**
     * Get only active procedures
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
