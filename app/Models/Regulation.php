<?php

namespace App\Models;

use App\Models\Category;
use App\Models\RegulationRelation;
use App\Models\RegulationVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regulation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'number',
        'year',
        'publish_date',
        'status',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'year'         => 'integer',
    ];

    // ── Relasi ──────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(RegulationVersion::class);
    }

    public function activeVersion(): HasOne
    {
        return $this->hasOne(RegulationVersion::class)->where('is_active', true);
    }

    /** Relasi sebagai source (A mengubah/mencabut B) */
    public function outgoingRelations(): HasMany
    {
        return $this->hasMany(RegulationRelation::class, 'source_regulation_id');
    }

    /** Relasi sebagai target (diubah/dicabut oleh A) */
    public function incomingRelations(): HasMany
    {
        return $this->hasMany(RegulationRelation::class, 'target_regulation_id');
    }

    // ── Helpers ─────────────────────────────────────────────

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Upload versi PDF baru.
     * Nonaktifkan versi sebelumnya, lalu buat versi baru yang aktif.
     */
    public function addVersion(array $data): RegulationVersion
    {
        $this->versions()->where('is_active', true)->update(['is_active' => false]);

        $nextVersion = ($this->versions()->max('version_number') ?? 0) + 1;

        return $this->versions()->create(array_merge($data, [
            'version_number' => $nextVersion,
            'is_active'      => true,
            'download_count' => 0,
        ]));
    }
}
