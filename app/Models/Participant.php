<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = ['user_id', 'reunion_id', 'guest_name', 'guest_email', 'response_status', 'presence', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reunion()
    {
        return $this->belongsTo(Reunion::class);
    }
}
