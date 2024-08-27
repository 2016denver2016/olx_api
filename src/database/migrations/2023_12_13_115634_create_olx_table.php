<?php

use App\Models\Flowk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOlxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('olx', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable(false);
            $table->string('email')->nullable(false);
            $table->integer('advert_id')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('status')->nullable(false);
            $table->double('price')->nullable(false);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('olx');
    }
}
