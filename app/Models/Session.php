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
        'start_time',
        'end_time',
        'location',
        'session_chair',
        'order',
        'type'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function programBook()
    {
        return $this->belongsTo(ProgramBook::class);
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class)->orderBy('order');
    }
}
