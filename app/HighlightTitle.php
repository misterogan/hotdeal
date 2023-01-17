<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HighlightTitle extends Model
{
    protected $table = 'highlight_titles';
    protected $fillable = [
        'id',
        'title',
        'section'
    ];
}
