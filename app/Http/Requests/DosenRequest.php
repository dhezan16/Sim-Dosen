<?php

namespace App\Http\Requests;

use App\Models\Dosen;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        $dosen = $this->route('dosen');

        return $dosen instanceof Dosen
            ? $this->user()->can('update', $dosen)
            : $this->user()->can('create', Dosen::class);
    }

    /**
     * Dosen yang mengubah datanya sendiri hanya boleh mengirim field biodata.
     * Field lain tidak divalidasi sehingga otomatis tidak ikut tersimpan.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $dosen = $this->route('dosen') ?? new Dosen;
        $unik = fn (string $kolom) => Rule::unique('dosen', $kolom)->ignore($dosen->id);

        $biodata = [
            'gelar_depan' => ['nullable', 'string', 'max:50'],
            'gelar_belakang' => ['nullable', 'string', 'max:100'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'email' => ['nullable', 'email', 'max:255', $unik('email')],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'bidang_keahlian' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto' => ['nullable', 'boolean'],
        ];

        if (! $this->user()->can('updateAkademik', $dosen)) {
            return $biodata;
        }

        $prodi = ['required', 'exists:program_studi,id'];

        // Operator hanya boleh menempatkan dosen pada prodinya sendiri.
        if ($this->user()->isOperator()) {
            $prodi[] = Rule::in([$this->user()->program_studi_id]);
        }

        return $biodata + [
            'nomor_registrasi' => ['required', 'string', 'max:255', $unik('nomor_registrasi')],
            'nidn' => ['nullable', 'digits_between:8,20', $unik('nidn')],
            'nuptk' => ['nullable', 'digits_between:8,20', $unik('nuptk')],
            'nip' => ['nullable', 'digits_between:8,20', $unik('nip')],
            'nama' => ['required', 'string', 'max:255'],
            'tmt_kerja' => ['nullable', 'date'],
            'program_studi_id' => $prodi,
            'jabatan_fungsional_id' => ['required', 'exists:jabatan_fungsional,id'],
            'kepangkatan_id' => ['required', 'exists:kepangkatan,id'],
            'pendidikan_id' => ['required', 'exists:pendidikan,id'],
            'rumpun_ilmu_id' => ['required', 'exists:rumpun_ilmu,id'],
            'status_serdos_id' => ['required', 'exists:status_serdos,id'],
            'status_pegawai_id' => ['required', 'exists:status_pegawai,id'],
            'ikatan_kerja_id' => ['required', 'exists:ikatan_kerja,id'],
            'status_aktivitas_id' => ['required', 'exists:status_aktivitas,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'program_studi_id.in' => 'Anda hanya dapat mengelola dosen pada program studi Anda sendiri.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nomor_registrasi' => 'nomor registrasi',
            'nidn' => 'NIDN',
            'nuptk' => 'NUPTK',
            'nip' => 'NIP',
            'tanggal_lahir' => 'tanggal lahir',
            'tmt_kerja' => 'TMT kerja',
            'program_studi_id' => 'program studi',
            'jabatan_fungsional_id' => 'jabatan fungsional',
            'kepangkatan_id' => 'kepangkatan',
            'pendidikan_id' => 'pendidikan',
            'rumpun_ilmu_id' => 'rumpun ilmu',
            'status_serdos_id' => 'status serdos',
            'status_pegawai_id' => 'status pegawai',
            'ikatan_kerja_id' => 'ikatan kerja',
            'status_aktivitas_id' => 'status aktivitas',
        ];
    }
}
