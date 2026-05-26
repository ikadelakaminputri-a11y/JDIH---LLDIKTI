<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegulationVersion extends Model
{
    public $timestamps = false; // hanya ada created_at, di-set manual

    protected $fillable = [
        'regulation_id',
        'version_number',
        'file_path',
        'file_size',
        'is_active',
        'uploaded_by',
        'notes',
        'download_count',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'created_at'     => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (RegulationVersion $version) {
            $version->created_at = now();
        });
    }

    // ── Relasi ──────────────────────────────────────────────

    public function regulation(): BelongsTo
    {
        return $this->belongsTo(Regulation::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ── Helpers ─────────────────────────────────────────────

    /** Increment download counter. */
    public function incrementDownload(): void
    {
        $this->increment('download_count');
    }

    /** Ukuran file dalam format manusia-baca. */
    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024) return "{$bytes} B";
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }
}
