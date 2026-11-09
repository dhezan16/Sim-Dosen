<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan data biodata dosen yang umum dipakai pada SIM Dosen.
     */
    public function up(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->string('nidn', 20)->nullable()->unique()->after('nuptk');
            $table->string('gelar_depan', 50)->nullable()->after('nama');
            $table->string('gelar_belakang', 100)->nullable()->after('gelar_depan');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('gelar_belakang');
            $table->string('tempat_lahir', 100)->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('email')->nullable()->unique()->after('tanggal_lahir');
            $table->string('no_hp', 20)->nullable()->after('email');
            $table->text('alamat')->nullable()->after('no_hp');
            $table->string('bidang_keahlian')->nullable()->after('alamat');
            $table->date('tmt_kerja')->nullable()->after('bidang_keahlian');
            $table->string('foto')->nullable()->after('tmt_kerja');
        });
    }

    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropUnique(['nidn']);
            $table->dropUnique(['email']);
            $table->dropColumn([
                'nidn', 'gelar_depan', 'gelar_belakang', 'jenis_kelamin', 'tempat_lahir',
                'tanggal_lahir', 'email', 'no_hp', 'alamat', 'bidang_keahlian', 'tmt_kerja', 'foto',
            ]);
        });
    }
};
