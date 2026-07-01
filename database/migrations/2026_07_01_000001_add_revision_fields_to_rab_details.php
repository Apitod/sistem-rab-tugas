<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('rab_details', function (Blueprint $table) {
            $table->boolean('revision_flag')->default(false)->after('total_price');
            $table->text('revision_reason')->nullable()->after('revision_flag');
        });
    }

    public function down(): void {
        Schema::table('rab_details', function (Blueprint $table) {
            $table->dropColumn(['revision_flag', 'revision_reason']);
        });
    }
};
