<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceedings extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'title',
        'isbn',
        'issn',
        'publisher',
        'publication_date',
        'front_matter_file',
        'back_matter_file',
        'cover_image',
        'status'
    ];

    protected $casts = [
        'publication_date' => 'date'
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    public static function statusOptions()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_ARCHIVED => 'Archived'
        ];
    }

    // Relationships
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    // Helpers
    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function getStatusNameAttribute()
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }
}
