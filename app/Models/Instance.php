<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instance extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];

    public function members()
    {
        return $this->hasMany(InstanceMember::class);
    }

    public function reunions()
    {
        return $this->hasMany(Reunion::class);
    }
}
