<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class diagnosis_histories extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'diagnose',
        'skin_image',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
