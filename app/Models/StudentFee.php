<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentFee extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'current_class', 'fees_due'];

    public function user(): BelongsTo
    {
       return $this->belongsTo(User::class);
    }

}
