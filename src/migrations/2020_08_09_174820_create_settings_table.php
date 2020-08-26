<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('group')->nullable();
            $table->longText('description')->nullable();
            $table->string('type');
            $table->integer('value_int')->nullable();
            $table->double('value_double')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->string('value_varchar')->nullable();
            $table->longText('value_longtext')->nullable();
            $table->date('value_date')->nullable();
            $table->dateTime('value_datetime')->nullable();
            $table->timestamp('value_timestamp')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
