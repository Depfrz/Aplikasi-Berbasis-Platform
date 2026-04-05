<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matakuliah extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_mk',
        'nama',
        'sks',
    ];

    protected $dates = ['deleted_at'];

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_matakuliah');
    }

    public function setKodeMkAttribute($value)
    {
        $this->attributes['kode_mk'] = strtoupper($value);
    }
}
