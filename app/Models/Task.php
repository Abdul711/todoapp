<?php
namespace App\Models;
require_once __DIR__.'/model.php';

use App\Support\Str;
class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'completed'
    ];
     public function getTitleAttribute($value)
    {
        return Str::lower($value); // Always return name in uppercase
    }
}