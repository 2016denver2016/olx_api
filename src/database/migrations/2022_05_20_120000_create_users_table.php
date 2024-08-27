<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 128)->unique();
            $table->string('email_verification_code')->nullable(true);
            $table->timestampTz('email_verified_at')->nullable(true);

            $table->string('password', 255);
            $table->string('password_recovery_token', 32)->nullable(true);
            $table->dateTime('password_recovery_token_created_at')->nullable(true);
            $table->smallInteger('status')->default(User::STATUS_WAITING_APPROVAL);
            $table->timestampsTz();
            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->bigInteger('deleted_by')->nullable(true);
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
