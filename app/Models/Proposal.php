<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposal';
    protected $fillable = [
        'judul',
        'tema',
        'using_latar_belakang',
        'using_tujuan',
        'fileName',
        'uploaded_by'
    ];
}
