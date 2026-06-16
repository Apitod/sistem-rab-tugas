<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rab_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('proposed_date');
            $table->decimal('total_budget', 15, 2)->default(0);
            $table->enum('status', ['pending_kaprodi','pending_wd','pending_dekan','disetujui','revisi','ditolak'])->default('pending_kaprodi');
            $table->string('tor_file_path');
            $table->string('rab_number', 100)->nullable();
            $table->string('signature_path')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rab_proposals'); }
};