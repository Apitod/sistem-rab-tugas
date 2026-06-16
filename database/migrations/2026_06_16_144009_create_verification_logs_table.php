<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_proposal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('verifier_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status_checked', ['verifikasi_ok','revisi','ditolak']);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('verification_logs'); }
};