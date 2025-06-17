<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'name',
        'title',
        'organization',
        'bio',
        'photo_path',
        'email',
        'website',
        'twitter',
        'linkedin'
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class);
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo_path ? Storage::url($this->photo_path) : null;
    }
}
