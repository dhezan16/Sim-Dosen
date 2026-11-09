<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penelitian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosen')->cascadeOnDelete();
            $table->string('judul', 500);
            $table->year('tahun');
            $table->string('skema')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->unsignedBigInteger('jumlah_dana')->nullable();
            $table->string('peran', 20)->default('Ketua');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penelitian');
    }
};
