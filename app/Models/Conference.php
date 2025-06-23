<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Conference extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'acronym',
        'description',
        'location',
        'start_date',
        'end_date',
        'website',
        'logo',
        'submission_deadline',
        'review_deadline',
        'notification_date',
        'camera_ready_deadline'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'submission_deadline' => 'date',
        'review_deadline' => 'date',
        'notification_date' => 'date',
        'camera_ready_deadline' => 'date'
    ];

    // Status constants
    const STATUS_UPCOMING = 'upcoming';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';

    public static function statusOptions()
    {
        return [
            self::STATUS_UPCOMING => 'Upcoming',
            self::STATUS_ONGOING => 'Ongoing',
            self::STATUS_COMPLETED => 'Completed'
        ];
    }

    public function getStatusAttribute()
    {
        $now = now();

        if ($now->gt($this->end_date)) {
            return self::STATUS_COMPLETED;
        } elseif ($now->gte($this->start_date) && $now->lte($this->end_date)) {
            return self::STATUS_ONGOING;
        } else {
            return self::STATUS_UPCOMING;
        }
    }

    public function getStatusNameAttribute()
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    public function proceedings()
    {
        return $this->hasMany(Proceedings::class);
    }

    public function programBook()
    {
        return $this->hasOne(ProgramBook::class);
    }

    public function chairs()
    {
        return $this->belongsToMany(User::class, 'conference_chairs');
    }

    // public function programChairs()
    // {
    //     return $this->belongsToMany(User::class, 'conference_program_chairs');
    // }

    public function isAcceptingSubmissions()
    {
        return $this->submission_deadline && now()->lte($this->submission_deadline);
    }

    public function getSubmissionDeadlineAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function activePapers()
    {
        return $this->papers();
    }

    public function programCommittees()
    {
        return $this->hasMany(ProgramCommittee::class);
    }

    // public function reviewers()
    // {
    //     return $this->programCommittees()
    //         ->where('status', 'accepted')
    //         ->where('role', 'reviewer')
    //         ->with('user');
    // }

    // public function areaChairs()
    // {
    //     return $this->programCommittees()
    //         ->where('status', 'accepted')
    //         ->where('role', 'area_chair')
    //         ->with('user');
    // }

    // public function programChairs()
    // {
    //     return $this->programCommittees()
    //         ->where('status', 'accepted')
    //         ->where('role', 'program_chair')
    //         ->with('user');
    // }

    public function acceptedProgramCommittees()
    {
        return $this->programCommittees()->where('status', 'accepted');
    }

    public function reviewers()
    {
        return $this->acceptedProgramCommittees()->where('role', 'reviewer');
    }

    public function areaChairs()
    {
        return $this->acceptedProgramCommittees()->where('role', 'area_chair');
    }

    public function programChairs()
    {
        return $this->acceptedProgramCommittees()->where('role', 'program_chair');
    }

    public function hasUserInProgramCommittee(User $user)
    {
        return $this->programCommittees()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
    }

    public function acceptedPapers()
    {
        return $this->papers()->where('status', 'accepted');
    }

    public function rejectedPapers()
    {
        return $this->papers()->where('status', 'rejected');
    }

    public function pendingPapers()
    {
        return $this->papers()->where('status', 'pending');
    }

    public function getSubmissionStatsAttribute()
    {
        return [
            'total' => $this->papers()->count(),
            'accepted' => $this->acceptedPapers()->count(),
            'rejected' => $this->rejectedPapers()->count(),
            'pending' => $this->pendingPapers()->count(),
            'approved_for_proceedings' => $this->papers()
                ->where('status', 'accepted')
                ->where('approved_for_proceedings', true)
                ->count(),
        ];
    }

    public function getReviewStatsAttribute()
    {
        $reviews = $this->papers()->with('reviews')->get()
            ->pluck('reviews')
            ->flatten();

        if ($reviews->isEmpty()) {
            return null;
        }

        return [
            'total_reviews' => $reviews->count(),
            'average_score' => $reviews->avg('score'),
            'score_distribution' => $reviews->groupBy('score')->map->count(),
        ];
    }
}
