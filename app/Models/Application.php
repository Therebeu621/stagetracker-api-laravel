<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company',
        'position',
        'location',
        'status',
        'applied_at',
        'next_followup_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'date',
            'next_followup_at' => 'date',
        ];
    }

    /**
     * Get the followups for this application.
     */
    public function followups(): HasMany
    {
        return $this->hasMany(Followup::class);
    }

    /**
     * Get the user that owns this application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
