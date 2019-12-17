<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Offer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        Schema::create('offer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kod_produktu');
            $table->integer('ilosc');
            $table->string('rok_produkcji');
            $table->double('cena', 15, 2);
            // $table->timestamps();
        });

        Schema::create('stats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('value');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer');
        Schema::dropIfExists('stats');

    }
}
