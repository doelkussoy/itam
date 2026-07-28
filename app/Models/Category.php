<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'spec_definitions'];

    protected $casts = [
        'spec_definitions' => 'array',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
