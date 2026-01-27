<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrLoger extends Model
{
    protected $table = 'tr_loger_lpc2';

    public $timestamps = false;

    protected $fillable = [
        'datetime',
        'ms',
        'l_gate_front',
        'l_gate_rear',
        'l_chamber_1',
        'l_chamber_2',
        'r_gate_front',
        'r_gate_rear',
        'r_chamber_1',
        'r_chamber_2',
    ];

    protected $casts = [
        'l_gate_front' => 'float',
        'l_gate_rear' => 'float',
        'l_chamber_1' => 'float',
        'l_chamber_2' => 'float',
        'r_gate_front' => 'float',
        'r_gate_rear' => 'float',
        'r_chamber_1' => 'float',
        'r_chamber_2' => 'float',
        'datetime' => 'datetime',
    ];
}
