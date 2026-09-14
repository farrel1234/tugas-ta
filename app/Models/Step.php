<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'image_path', 'step_order', 'text'];

    protected $appends = ['image_url'];

    // Relasi kebalikan: 1 Step (Slide) ini milik 1 Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image_path) {
            return asset('templating/img/polibatam.png');
        }
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }
        if (str_starts_with($this->image_path, 'templating/')) {
            return asset($this->image_path);
        }
        return asset('storage/' . $this->image_path);
    }
}
