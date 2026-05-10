<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstanceMember extends Model
{
    protected $table = 'instance_user';
    protected $fillable = ['instance_id', 'user_id', 'guest_name', 'guest_email'];

    public function instance()
    {
        return $this->belongsTo(Instance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
