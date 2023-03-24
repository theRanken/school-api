<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClasses extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'fees'
    ];

    public function fees(){
        return $this->fees;
    }
}
