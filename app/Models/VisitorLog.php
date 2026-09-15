<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke data User / Anggota jika terdaftar.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
