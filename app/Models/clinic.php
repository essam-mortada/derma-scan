<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'speciality',
        'created_at',
        'updated_at'
    ];
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
