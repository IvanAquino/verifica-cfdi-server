<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sat_validations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('version', 10)->nullable();
            $table->string('issuer_rfc', 13)->nullable();
            $table->string('receiver_rfc', 13)->nullable();
            $table->string('total', 32)->nullable();
            $table->string('seal', 64)->nullable();
            $table->string('response_code', 120)->nullable();
            $table->string('cfdi_status', 40)->nullable();
            $table->string('cancellable_status', 50)->nullable();
            $table->string('cancellation_status', 50)->nullable();
            $table->string('efos_validation', 10)->nullable();
            $table->timestamps();

            $table->unique('uuid');
            $table->index(['issuer_rfc', 'receiver_rfc']);
            $table->index('response_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sat_validations');
    }
};
