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
        Schema::table('program_studi', function (Blueprint $table) {
            // Catatan: nama file tidak sesuai isi (historis). Migration ini
            // menambahkan kolom jenjang (S1, S2, D3, dst.) ke program studi.
            $table->string('jenjang', 10)->default('S1')->after('fakultas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studi', function (Blueprint $table) {
            $table->dropColumn('jenjang');
        });
    }
};
