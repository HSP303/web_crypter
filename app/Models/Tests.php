<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tests extends Model
{
    protected $fillable = [
        'file_id',
        'score',
        'qtdtests',
        'description',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(Files::class);
    }
}
