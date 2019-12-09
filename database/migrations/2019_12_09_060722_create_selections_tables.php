<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMostRatedTable extends Migration
{

    /**
     * @param Blueprint $table
     */
    public static function tableFields(Blueprint $table): void
    {
        $table->bigIncrements('id');
        $table->string('imdbID');
        $table->double('rating');
        $table->bigInteger('votes');
        $table->string('title')->nullable();
        $table->string('type')->nullable();
        $table->string('year')->nullable();
        $table->string('poster')->nullable();
        $table->timestamps();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('most_rated', static function (Blueprint $table) {
            static::tableFields($table);
        });

        Schema::create('most_popular', static function (Blueprint $table) {
            static::tableFields($table);
        });

        Schema::create('least_popular', static function (Blueprint $table) {
            static::tableFields($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('most_rated');
        Schema::dropIfExists('most_popular');
        Schema::dropIfExists('least_popular');
    }
}
