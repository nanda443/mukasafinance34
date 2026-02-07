<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name'     => $row['nama'],
            'email'    => $row['email'],
            'nis'      => $row['nis'],
            'password' => Hash::make($row['nis']), // Default password is NIS
            'role'     => 'siswa',
            'kelas'    => (string) $row['kelas'],
            'jurusan'  => $row['jurusan'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'nis' => 'required|unique:users,nis',
            'kelas' => 'required',
            'jurusan' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email.unique' => 'Email :input sudah terdaftar.',
            'nis.unique' => 'NIS :input sudah terdaftar.',
        ];
    }
}
