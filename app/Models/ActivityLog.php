<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Removes suffixes such as "(record #61)" or "(consultation #12)" from log descriptions.
     * Strips everywhere in the string so legacy rows still read cleanly at display time.
     */
    public static function sanitizeDescriptionForDisplay(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $out = preg_replace('/\s*\((record|consultation)\s*#\d+\)/iu', ' ', $value) ?? $value;

        return trim(preg_replace('/\s+/u', ' ', $out) ?? '');
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => self::sanitizeDescriptionForDisplay($value),
        );
    }
}
