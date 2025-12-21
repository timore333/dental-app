<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class InsuranceCompany extends Model
{
    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'contact_person',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function priceLists(): HasMany
    {
        return $this->hasMany(InsuranceCompanyPriceList::class);
    }

    public function insuranceRequests(): HasMany
    {
        return $this->hasMany(InsuranceRequest::class);
    }

    public function approvals(): HasManyThrough
    {
        return $this->hasManyThrough(InsuranceApproval::class, InsuranceRequest::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ==================== METHODS ====================

    /**
     * Get price for a specific procedure
     */
    public function getPriceForProcedure(int $procedureId): ?float
    {
        return $this->priceLists()
            ->where('procedure_id', $procedureId)
            ->value('price');
    }

    /**
     * Get complete price list as array
     */
    public function getPriceList(): array
    {
        return $this->priceLists()
            ->with('procedure')
            ->get()
            ->mapWithKeys(fn ($item) => [
                $item->procedure->code => $item->price
            ])
            ->toArray();
    }

    /**
     * Check if this company has a price for a procedure
     */
    public function hasPriceForProcedure(int $procedureId): bool
    {
        return $this->getPriceForProcedure($procedureId) !== null;
    }
}
