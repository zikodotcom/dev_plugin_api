<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pages extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pages';
    protected $fillable = [
        'pageName',
        'filePath',
        'id_projects'
    ];
}
