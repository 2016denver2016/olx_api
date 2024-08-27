<?php

use App\Models\DeviceSession;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('device_sessions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable(false);
            $table->string('device_id')->nullable(true);
            $table->smallInteger('device_type')->nullable(false);
            $table->string('push_id')->nullable(true);
            $table->string('auth_token')->nullable(false);
            $table->timestampTz('valid_until')->nullable(false);
            $table->timestampsTz();

            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('device_sessions');
    }
}
