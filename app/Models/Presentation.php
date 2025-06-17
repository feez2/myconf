<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'paper_id',
        'title',
        'abstract',
        'start_time',
        'end_time',
        'speaker_name',
        'speaker_affiliation',
        'speaker_bio',
        'speaker_photo_path',
        'order'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }
}
