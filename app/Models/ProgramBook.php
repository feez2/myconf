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
        'date',
        'welcome_message',
        'general_information',
        'cover_image_path'
    ];

    protected $casts = [
        'date' => 'date',
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
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($session) {
                return $session->start_time->format('Y-m-d');
            });
    }
}
