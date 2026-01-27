<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wa_loger_lpdc20191029', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_part')->nullable();
            $table->timestamp('datetime_stamp')->nullable();
            $table->float('r_lower_gate1')->nullable();
            $table->float('r_lower_main1')->nullable();
            $table->float('l_lower_gate1')->nullable();
            $table->float('l_lower_main1')->nullable();
            $table->float('cooling_water')->nullable();
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('day')->nullable();
            $table->integer('hour')->nullable();
            $table->integer('min')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_loger_lpdc20191029');
    }
};
