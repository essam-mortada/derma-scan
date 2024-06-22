<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointment extends Model
{
    use HasFactory;


    protected $fillable = ['clinic_id', 'user_id', 'date'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $dates = ['date'];

    public function hasPassed()
    {
        return $this->date->isPast();
    }
}
