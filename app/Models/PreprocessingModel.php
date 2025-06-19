<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreprocessingModel extends Model
{
    protected $table = 'preprocessing';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'data_clean',
        'lowercasing',
        'remove_punctuation',
        'tokenizing',
        'stopword',
        'stemming',
        'sentiment',
    ];
}
