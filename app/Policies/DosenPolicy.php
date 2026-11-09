<?php

namespace App\Policies;

use App\Models\Dosen;
use App\Models\User;

/**
 * Hak akses data dosen per peran:
 *
 * | Aksi              | Admin | Operator (prodinya) | Dosen (dirinya) |
 * |-------------------|-------|---------------------|-----------------|
 * | Lihat daftar      | ✓     | ✓                   | –               |
 * | Lihat detail      | ✓     | ✓                   | ✓               |
 * | Tambah            | ✓     | ✓                   | –               |
 * | Ubah biodata      | ✓     | ✓                   | ✓               |
 * | Ubah data akademik| ✓     | ✓                   | –               |
 * | Kelola Tri Dharma | ✓     | ✓                   | ✓               |
 * | Hapus             | ✓     | ✓                   | –               |
 */
class DosenPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(User::ADMIN, User::OPERATOR);
    }

    public function view(User $user, Dosen $dosen): bool
    {
        return $this->kelola($user, $dosen) || $this->milikSendiri($user, $dosen);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || ($user->isOperator() && $user->program_studi_id !== null);
    }

    public function update(User $user, Dosen $dosen): bool
    {
        return $this->view($user, $dosen);
    }

    /**
     * Data identitas resmi & kepegawaian (prodi, jabatan, status, dll.)
     * hanya boleh diubah admin/operator, bukan oleh dosen sendiri.
     */
    public function updateAkademik(User $user, Dosen $dosen): bool
    {
        return $dosen->exists ? $this->kelola($user, $dosen) : $this->create($user);
    }

    public function delete(User $user, Dosen $dosen): bool
    {
        return $this->kelola($user, $dosen);
    }

    private function kelola(User $user, Dosen $dosen): bool
    {
        return $user->isAdmin()
            || ($user->isOperator() && $user->program_studi_id !== null && $user->program_studi_id === $dosen->program_studi_id);
    }

    private function milikSendiri(User $user, Dosen $dosen): bool
    {
        return $user->isDosen() && $user->dosen_id !== null && $user->dosen_id === $dosen->id;
    }
}
