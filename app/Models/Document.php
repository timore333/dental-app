<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'uploaded_by',
    ];

    protected $casts = [
        'type' => 'string', // insurance_card, service_request, approval, prescription, receipt, other
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Polymorphic - can belong to Patient, InsuranceCompany, etc.
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    // ==================== METHODS ====================

    /**
     * Get file URL
     */
    public function getUrl(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Delete file and record
     */
    public function deleteFile(): bool
    {
        if (Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
        return $this->delete();
    }
}
