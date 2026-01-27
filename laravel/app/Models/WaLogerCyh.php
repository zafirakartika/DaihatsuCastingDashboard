<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaLogerCyh extends Model
{
    protected $connection = 'mysql'; // Uses default connection to 'alpc' database
    protected $table = 'wa_loger_cyh_wa';
    protected $primaryKey = 'no_shot';
    public $timestamps = false;

    protected $fillable = [
        'no_shot',
        'id_part',
        'datetime_stamp'
    ];

    protected $casts = [
        'no_shot' => 'integer'
    ];
}
