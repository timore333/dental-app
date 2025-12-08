<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class InsuranceCompany extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'registration_number',
        'phone',
        'email',
        'contact_person',
        'address',
        'city',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * All patients with this insurance
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Price list for procedures
     */
    public function priceLists(): HasMany
    {
        return $this->hasMany(InsuranceCompanyPriceList::class);
    }

    /**
     * All insurance requests from this company
     */
    public function insuranceRequests(): HasMany
    {
        return $this->hasMany(InsuranceRequest::class);
    }

    /**
     * All insurance approvals through requests
     */
    public function approvals(): HasManyThrough
    {
        return $this->hasManyThrough(InsuranceApproval::class, InsuranceRequest::class);
    }

    /**
     * All documents - polymorphic
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Insurance company's financial account - polymorphic
     */
    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    // ==================== SCOPES ====================

    /**
     * Get only active insurance companies
     */
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
}
