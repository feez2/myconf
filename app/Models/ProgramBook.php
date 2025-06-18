<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'title',
        'start_date',
        'end_date',
        'welcome_message',
        'general_information',
        'cover_image_path'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class)->orderBy('order');
    }

    public function getScheduleByDayAttribute()
    {
        return $this->sessions()
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('date');
    }
}
