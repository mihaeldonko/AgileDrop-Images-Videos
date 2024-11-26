<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',           
        'description',     
        'file_path',      
        'file_type',       
        'file_size',
        'user_id',     
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
