<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegulationRelation extends Model
{

    protected $fillable = [
        'source_regulation_id',
        'target_regulation_id',
        'relation_type',
    ];

    // ── Relasi ──────────────────────────────────────────────

    public function sourceRegulation(): BelongsTo
    {
        return $this->belongsTo(Regulation::class, 'source_regulation_id');
    }

    public function targetRegulation(): BelongsTo
    {
        return $this->belongsTo(Regulation::class, 'target_regulation_id');
    }
 
    // ── Label tampilan ──────────────────────────────────────

    /**
     * Label yang ditampilkan dari sudut pandang source.
     * Contoh: "Mengubah: Peraturan XYZ"
     */
    public function getLabelFromSourceAttribute(): string
    {
        return match ($this->relation_type) {
            'mengubah'        => 'Mengubah',
            'mencabut'        => 'Mencabut',
            'dicabut_sebagian' => 'Mencabut sebagian',
            default           => $this->relation_type,
        };
    }

    /**
     * Label yang ditampilkan dari sudut pandang target.
     * Contoh: "Diubah dengan: Peraturan ABC"
     */
    public function getLabelFromTargetAttribute(): string
    {
        return match ($this->relation_type) {
            'mengubah'        => 'Diubah dengan',
            'mencabut'        => 'Dicabut oleh',
            'dicabut_sebagian' => 'Dicabut sebagian oleh',
            default           => $this->relation_type,
        };
    }
}
