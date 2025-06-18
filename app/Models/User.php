<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'affiliation',
        'country',
        'bio'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    public function paperAuthors()
    {
        return $this->hasMany(PaperAuthor::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function chairedConferences()
    {
        return $this->belongsToMany(Conference::class, 'conference_chairs')
                    ->withTimestamps();
    }

    public function programChairedConferences()
    {
        return $this->belongsToMany(Conference::class, 'conference_program_chairs')
                    ->withTimestamps();
    }

    public function programCommittees()
    {
        return $this->hasMany(ProgramCommittee::class);
    }

    public function isProgramCommitteeMember($conferenceId)
    {
        return $this->programCommittees()
            ->where('conference_id', $conferenceId)
            ->where('status', 'accepted')
            ->exists();
    }

    public function isProgramChair(Conference $conference)
    {
        return $this->programCommittees()
            ->where('conference_id', $conference->id)
            ->where('role', ProgramCommittee::ROLE_PROGRAM_CHAIR)
            ->where('status', ProgramCommittee::STATUS_ACCEPTED)
            ->exists();
    }

    public function isAreaChair(Conference $conference)
    {
        return $this->programCommittees()
            ->where('conference_id', $conference->id)
            ->where('role', ProgramCommittee::ROLE_AREA_CHAIR)
            ->where('status', ProgramCommittee::STATUS_ACCEPTED)
            ->exists();
    }

    public function isReviewer(Conference $conference)
    {
        return $this->programCommittees()
            ->where('conference_id', $conference->id)
            ->where('role', ProgramCommittee::ROLE_REVIEWER)
            ->where('status', ProgramCommittee::STATUS_ACCEPTED)
            ->exists();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAuthor()
    {
        return $this->role === 'author';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
