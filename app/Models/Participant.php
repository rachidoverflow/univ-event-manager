<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = ['user_id', 'reunion_id', 'response_status', 'presence'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reunion()
    {
        return $this->belongsTo(Reunion::class);
    }
}
