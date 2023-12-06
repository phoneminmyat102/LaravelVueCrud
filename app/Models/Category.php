<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            foreach ($category->items as $each) {
                if(Storage::disk('public')->exists($each->image)){
                    Storage::disk('public')->delete($each->image);
                }
            }
        });
    }
}
