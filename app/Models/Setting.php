<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'app_name',
        'institution_name',
        'institution_short',
        'year',
        'address',
        'description',
        'logo',
        'favicon',
        'qr_url',
        'qr_path',
        'scan_count',
    ];
}
