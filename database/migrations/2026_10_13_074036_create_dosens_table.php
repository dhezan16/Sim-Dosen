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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_registrasi')->unique();
            $table->string('nuptk')->nullable()->unique();
            $table->string('nama');
            $table->string('nip')->nullable()->unique();

            $table->foreignId('program_studi_id')
                ->constrained('program_studi')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('jabatan_fungsional_id')
                ->constrained('jabatan_fungsional')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('kepangkatan_id')
                ->constrained('kepangkatan')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('pendidikan_id')
                ->constrained('pendidikan')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('rumpun_ilmu_id')
                ->constrained('rumpun_ilmu')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('status_serdos_id')
                ->constrained('status_serdos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('status_pegawai_id')
                ->constrained('status_pegawai')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('ikatan_kerja_id')
                ->constrained('ikatan_kerja')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('status_aktivitas_id')
                ->constrained('status_aktivitas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
