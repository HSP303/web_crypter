<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Files extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'filename', 'size', 'user'];

    public function test(): HasMany
    {
        return $this->hasMany(Test::class);
    }
}

