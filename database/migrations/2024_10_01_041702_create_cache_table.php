<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCacheTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->unique();
                $table->text('value');
                $table->integer('expiration');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('cache');
    }
}