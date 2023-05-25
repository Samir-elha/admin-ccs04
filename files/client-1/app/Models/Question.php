<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public function Answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function Questionaire()
    {
        return $this->belongsTo(Questionaire::class)->withDefault();
    }

    public function QuestionType()
    {
        return $this->belongsTo(QuestionType::class)->withDefault();
    }

    public function AnswerLists()
    {
        return $this->hasMany(AnswerList::class);
    }
}
