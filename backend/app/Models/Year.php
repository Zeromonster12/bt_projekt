<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $fillable = [
        'name' => 'string|max:9|unique|required|min:4',]; // format - 2024/2025 || 2024/25 || 2024

    public function createdBy() {
        return $this->belongsTo(User::class);
    }
}
