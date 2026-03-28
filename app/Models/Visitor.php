<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitors';

    protected $fillable = [
        'photo',           // ← tambahkan ini
        'full_name',
        'address',
        'institution',
        'phone_number',
        'purpose',
        'meet_with',
        'notes',
    ];
}
