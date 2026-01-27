<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaLoger extends Model
{
    protected $table = 'wa_loger_lpdc20191029';

    public $timestamps = false; // your table appears to have explicit datetime column

    protected $fillable = [
        'id_part',
        'datetime_stamp',
        'r_lower_gate1',
        'r_lower_main1',
        'l_lower_gate1',
        'l_lower_main1',
        'cooling_water',
        'year', 'month', 'day', 'hour', 'min', 'created_at'
    ];

    protected $casts = [
        'r_lower_gate1' => 'float',
        'r_lower_main1' => 'float',
        'l_lower_gate1' => 'float',
        'l_lower_main1' => 'float',
        'cooling_water' => 'float',
        'datetime_stamp' => 'datetime',
    ];
}
