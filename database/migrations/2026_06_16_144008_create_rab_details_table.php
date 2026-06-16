<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rab_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_proposal_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->integer('quantity');
            $table->string('unit', 50);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rab_details'); }
};