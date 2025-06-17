<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Paper extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'user_id',
        'title',
        'abstract',
        'keywords',
        'file_path',
        'status',
        'decision_comments',
        'decision_notes',
        'decision_made_at',
        'decision_made_by',
        'camera_ready_deadline',
        'proceedings_id',
        'camera_ready_file',
        'camera_ready_submitted_at',
        'copyright_form_file'
    ];

    protected $casts = [
        'decision_made_at' => 'datetime',
        'camera_ready_deadline' => 'datetime'
    ];

    // Status constants
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_REVISION_REQUIRED = 'revision_required';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';

    public static function statusOptions()
    {
        return [
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_REVISION_REQUIRED => 'Revision Required',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_WITHDRAWN => 'Withdrawn',
        ];
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authors()
    {
        return $this->hasMany(PaperAuthor::class)->orderBy('order');
    }

    public function correspondingAuthor()
    {
        return $this->hasOne(PaperAuthor::class)->where('is_corresponding', true);
    }

    public function mainAuthor()
    {
        return $this->hasOne(PaperAuthor::class)->where('order', 0);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageScoreAttribute()
    {
        return $this->reviews()->avg('score');
    }

    public function scopeForConference($query, $conferenceId)
    {
        return $query->where('conference_id', $conferenceId);
    }

    public function scopeForAuthor($query, $userId)
    {
        return $query->whereHas('authors', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function assignReviewers(array $reviewerIds)
    {
        foreach ($reviewerIds as $reviewerId) {
            Review::create([
                'paper_id' => $this->id,
                'reviewer_id' => $reviewerId,
                'score' => null,
                'comments' => null,
                'recommendation' => null,
                'status' => 'pending',
                'completed_at' => null,
            ]);
        }
    }

    public function decisionMaker()
    {
        return $this->belongsTo(User::class, 'decision_made_by');
    }

    public function proceedings()
    {
        return $this->belongsTo(Proceedings::class);
    }

    public function hasCameraReadyVersion()
    {
        return !is_null($this->camera_ready_file);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_ACCEPTED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_REVISION_REQUIRED => 'warning',
            self::STATUS_UNDER_REVIEW => 'info',
            default => 'secondary'
        };
    }

    public function getCameraReadyUrlAttribute()
    {
        return $this->camera_ready_path ? Storage::url($this->camera_ready_path) : null;
    }

    public function scopeInProceedings($query)
    {
        return $query->where('in_proceedings', true);
    }

    public function presentation()
    {
        return $this->hasOne(Presentation::class);
    }
}
