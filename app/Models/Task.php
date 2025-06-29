<?php
namespace App\Models;
require_once __DIR__.'/model.php';
class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'completed'
    ];
}