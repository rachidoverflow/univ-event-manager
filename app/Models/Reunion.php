<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    protected $fillable = ['titre', 'date', 'lieu', 'status', 'created_by'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class)->orderBy('ordre');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants')
            ->withPivot('response_status', 'presence')
            ->withTimestamps();
    }

    public function compteRendu()
    {
        return $this->hasOne(CompteRendu::class);
    }
}
