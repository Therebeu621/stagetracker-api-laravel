<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Followup extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'type',
        'done_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'done_at' => 'date',
        ];
    }

    /**
     * Get the application that owns this followup.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
