<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'question',
        'category',
        'explanation'
    ];

    // 1つのクイズは複数の選択肢を持つ
    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}