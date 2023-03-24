<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    use HasFactory;
    protected $fillable = ['current_class'];

    public function user()
    {
       return $this->belongsTo(User::class);
    }

}
