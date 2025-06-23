<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'paper_id',
        'reviewer_id',
        'score',
        'comments',
        'status',
        'confidential_comments',
        'completed_at',
        'recommendation',
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    // Recommendation options
    const RECOMMEND_ACCEPT = 'accept';
    const RECOMMEND_MINOR_REVISION = 'minor_revision';
    const RECOMMEND_MAJOR_REVISION = 'major_revision';
    const RECOMMEND_REJECT = 'reject';
    const RECOMMEND_PENDING = 'pending';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';

    public static function recommendationOptions()
    {
        return [
            self::RECOMMEND_ACCEPT => 'Accept',
            self::RECOMMEND_MINOR_REVISION => 'Minor Revision',
            self::RECOMMEND_MAJOR_REVISION => 'Major Revision',
            self::RECOMMEND_REJECT => 'Reject',
            self::RECOMMEND_PENDING => 'Pending'
        ];
    }

    public static function statusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed'
        ];
    }

    // Relationships
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // Helpers
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function getRecommendationNameAttribute()
    {
        return self::recommendationOptions()[$this->recommendation] ?? $this->recommendation;
    }
}
