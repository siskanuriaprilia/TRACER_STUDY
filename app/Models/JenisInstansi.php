<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisInstansi extends Model
{
    use HasFactory;

    protected $table = 'jenis_instansi';

    protected $fillable = [
        'jenis_instansi',
    ];

    public function lulusan()
    {
        return $this->hasMany(Lulusan::class);
    }
}
