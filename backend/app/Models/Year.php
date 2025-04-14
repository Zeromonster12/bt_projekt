<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $fillable = [
        'year', // Povolené atribúty pre mass assignment
        'user_id',
    ];

    public function createdBy() {
        return $this->belongsTo(User::class);
    }
}