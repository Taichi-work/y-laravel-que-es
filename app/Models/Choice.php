<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    protected $fillable = ['choice_text', 'is_correct', 'quiz_id'];

    // 1つの選択肢は1つのクイズに属する
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
