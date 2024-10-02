<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdTypeInOauthAccessTokens extends Migration
{
    public function up()
    {
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->string('id', 128)->change(); // Set to VARCHAR(128)
        });
    }

    public function down()
    {
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id')->change(); // Adjust back if necessary
        });
    }
}