<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompteRendu extends Model
{
    protected $table = 'compte_rendus';
    protected $fillable = ['reunion_id', 'file_path', 'file_name', 'uploaded_by'];

    public function reunion()
    {
        return $this->belongsTo(Reunion::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
