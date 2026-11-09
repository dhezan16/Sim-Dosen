<?php

/*
| Pesan validasi Bahasa Indonesia untuk aturan yang dipakai aplikasi.
| Aturan lain yang tidak tercantum akan memakai pesan bawaan (Inggris).
*/

return [
    'boolean' => ':Attribute harus bernilai benar atau salah.',
    'date' => ':Attribute bukan tanggal yang valid.',
    'before' => ':Attribute harus tanggal sebelum :date.',
    'digits_between' => ':Attribute harus berupa angka :min sampai :max digit.',
    'email' => ':Attribute harus berupa alamat email yang valid.',
    'exists' => ':Attribute yang dipilih tidak valid.',
    'image' => ':Attribute harus berupa gambar.',
    'in' => ':Attribute yang dipilih tidak valid.',
    'integer' => ':Attribute harus berupa bilangan bulat.',
    'max' => [
        'file' => ':Attribute maksimal :max kilobyte.',
        'numeric' => ':Attribute maksimal :max.',
        'string' => ':Attribute maksimal :max karakter.',
    ],
    'mimes' => ':Attribute harus berupa file bertipe: :values.',
    'min' => [
        'numeric' => ':Attribute minimal :min.',
        'string' => ':Attribute minimal :min karakter.',
    ],
    'required' => ':Attribute wajib diisi.',
    'string' => ':Attribute harus berupa teks.',
    'unique' => ':Attribute sudah digunakan.',
    'uploaded' => ':Attribute gagal diunggah.',
    'url' => ':Attribute harus berupa URL yang valid.',

    'attributes' => [
        'nama' => 'nama',
        'email' => 'email',
        'password' => 'kata sandi',
        'tempat_lahir' => 'tempat lahir',
        'jenis_kelamin' => 'jenis kelamin',
        'no_hp' => 'nomor HP',
        'gelar_depan' => 'gelar depan',
        'gelar_belakang' => 'gelar belakang',
        'bidang_keahlian' => 'bidang keahlian',
    ],
];
