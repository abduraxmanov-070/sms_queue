<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'phone',
        'pasport_seria',
        'address',
        'position'
    ];
}
