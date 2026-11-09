<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Peran pengguna:
     * - admin    : akses penuh
     * - operator : mengelola dosen pada satu program studi (program_studi_id)
     * - dosen    : hanya data milik sendiri (dosen_id)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('dosen')->after('password');
            $table->foreignId('program_studi_id')->nullable()->after('role')
                ->constrained('program_studi')->nullOnDelete();
            $table->foreignId('dosen_id')->nullable()->unique()->after('program_studi_id')
                ->constrained('dosen')->nullOnDelete();
        });

        // Pengguna yang sudah ada sebelumnya memiliki akses penuh, jadi dijadikan admin.
        DB::table('users')->update(['role' => 'admin']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['program_studi_id']);
            $table->dropForeign(['dosen_id']);
            $table->dropUnique(['dosen_id']);
            $table->dropColumn(['role', 'program_studi_id', 'dosen_id']);
        });
    }
};
