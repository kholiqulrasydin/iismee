<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presention extends Model
{
    use HasFactory;

    protected $table = 'presention';

    protected $fillable = [
        'mahasiswa_id',
        'selfies_file_name',
        'isK3Used',
        'todays_motivations',
        'isLate'
    ];
}
