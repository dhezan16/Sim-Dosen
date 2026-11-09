<?php

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
        Schema::table('fakultas', function (Blueprint $table) {
            $table->foreignId('universitas_id')
                ->nullable()
                ->constrained('universitas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fakultas', function (Blueprint $table) {
            $table->dropForeign(['universitas_id']);
            $table->dropColumn('universitas_id');
        });
    }
};
