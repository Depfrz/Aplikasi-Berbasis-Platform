<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'username',
    ];

    protected $dates = ['deleted_at'];

    public function matakuliahs()
    {
        return $this->belongsToMany(Matakuliah::class, 'dosen_matakuliah');
    }
}
