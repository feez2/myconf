<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramCommittee extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'user_id',
        'role',
        'status',
        'invitation_message',
        'invited_at',
        'responded_at'
    ];

    // Role constants
    const ROLE_REVIEWER = 'reviewer';
    const ROLE_AREA_CHAIR = 'area_chair';
    const ROLE_PROGRAM_CHAIR = 'program_chair';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    public static function roleOptions()
    {
        return [
            self::ROLE_REVIEWER => 'Reviewer',
            self::ROLE_AREA_CHAIR => 'Area Chair',
            self::ROLE_PROGRAM_CHAIR => 'Program Chair',
        ];
    }

    public static function statusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    // Relationships
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeForConference($query, $conferenceId)
    {
        return $query->where('conference_id', $conferenceId);
    }

    // Helper methods
    public function isAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function getRoleNameAttribute()
    {
        return self::roleOptions()[$this->role] ?? $this->role;
    }

    public function getStatusNameAttribute()
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }
}
