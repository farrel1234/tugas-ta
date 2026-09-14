<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    // Kolom yang boleh diisi datanya
    protected $fillable = ['num', 'title', 'desc'];

    // Relasi One to Many: 1 Menu punya banyak Steps (Slide)
    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('step_order', 'asc');
    }
}
