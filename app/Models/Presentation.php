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
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    // Helper method to get full datetime for start_time
    public function getStartDateTimeAttribute()
    {
        return $this->session->date->setTimeFrom($this->start_time);
    }

    // Helper method to get full datetime for end_time
    public function getEndDateTimeAttribute()
    {
        return $this->session->date->setTimeFrom($this->end_time);
    }
}
