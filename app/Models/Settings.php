<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'site_name',
        'site_url',
        'email_from',
        'email_to'
    ];

    public $timestamps = false;

}
