<?php

// Nama: Muhammad Daffa Fariza | NIM: 103012300004
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = [
        'nama',
        'nim',
        'email',
        'prodi',
        'ipk',
        'sks'
    ];
}
