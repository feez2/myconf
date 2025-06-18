<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_book_id',
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'session_chair',
        'order',
        'type'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function programBook()
    {
        return $this->belongsTo(ProgramBook::class);
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class)->orderBy('order');
    }

    // Helper method to get full datetime for start_time
    public function getStartDateTimeAttribute()
    {
        return $this->date->setTimeFrom($this->start_time);
    }

    // Helper method to get full datetime for end_time
    public function getEndDateTimeAttribute()
    {
        return $this->date->setTimeFrom($this->end_time);
    }
}
