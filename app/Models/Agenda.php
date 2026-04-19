<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'reunion_id',
        'titre',
        'description',
        'decision',
        'ordre',
    ];

    public function reunion()
    {
        return $this->belongsTo(Reunion::class);
    }
}
