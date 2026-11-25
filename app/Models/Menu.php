<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    // Kolom yang boleh diisi datanya
    protected $fillable = ['user_id', 'num', 'title', 'desc'];

    /**
     * Relasi kebalikan: Menu ini dikelola oleh User / Admin
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi One to Many: 1 Menu punya banyak Steps (Slide)
    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('step_order', 'asc');
    }

    /**
     * Urutkan nomor menu secara numerik alami, kompatibel dengan MySQL, SQLite, dan PostgreSQL.
     */
    public function scopeOrderByNaturalNum($query)
    {
        if ($query->getConnection()->getDriverName() === 'pgsql') {
            return $query->orderByRaw("CASE WHEN num ~ '^[0-9]+$' THEN CAST(num AS INTEGER) ELSE 999999 END ASC, num ASC");
        }

        return $query->orderByRaw('CAST(num AS UNSIGNED) ASC, num ASC');
    }
}
