<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\ProgramCommittee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PCInvitationMail;
use App\Models\Review;
use App\Models\Paper;

class ProgramCommitteeController extends Controller
{
    public function index(Conference $conference)
    {
        $this->authorize('managePC', $conference);

        $members = $conference->programCommittees()
            ->with('user')
            ->latest()
            ->get();

        $potentialMembers = User::where('role', 'reviewer')
            ->whereDoesntHave('programCommittees', function($query) use ($conference) {
                $query->where('conference_id', $conference->id);
            })
            ->orderBy('name')
            ->get();

        return view('conferences.pc-members', [
            'conference' => $conference,
            'members' => $members,
            'potentialMembers' => $potentialMembers,
            'roleOptions' => ProgramCommittee::roleOptions(),
            'statusOptions' => ProgramCommittee::statusOptions()
        ]);
    }

    public function store(Request $request, Conference $conference)
    {
        $this->authorize('managePC', $conference);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:' . implode(',', array_keys(ProgramCommittee::roleOptions())),
            'invitation_message' => 'nullable|string|max:500'
        ]);

        // Check if already invited
        if ($conference->programCommittees()->where('user_id', $validated['user_id'])->exists()) {
            return back()->with('error', 'This user has already been invited to the program committee.');
        }

        $invitation = ProgramCommittee::create([
            'conference_id' => $conference->id,
            'user_id' => $validated['user_id'],
            'role' => $validated['role'],
            'invitation_message' => $validated['invitation_message'],
            'status' => ProgramCommittee::STATUS_PENDING,
            'invited_at' => now()
        ]);

        // Send invitation email
        Mail::to($invitation->user->email)
            ->queue(new PCInvitationMail($invitation));

        return redirect()->route('pc-members.index', $conference)
            ->with('success', 'Invitation sent successfully!');
    }

    public function update(Request $request, ProgramCommittee $pcMember)
    {
        $this->authorize('respond', $pcMember);

        $validated = $request->validate([
            'response' => 'required|in:accept,reject'
        ]);

        $pcMember->update([
            'status' => $validated['response'] === 'accept'
                ? ProgramCommittee::STATUS_ACCEPTED
                : ProgramCommittee::STATUS_REJECTED,
            'responded_at' => now()
        ]);

        $message = $validated['response'] === 'accept'
            ? 'You have accepted the invitation to join the program committee.'
            : 'You have declined the invitation to join the program committee.';

        return redirect()->route('dashboard')
            ->with('success', $message);
    }

    public function destroy(Conference $conference, ProgramCommittee $pcMember)
    {
        $this->authorize('managePC', $conference);

        $pcMember->delete();

        return redirect()->route('pc-members.index', $conference)
            ->with('success', 'Committee member removed successfully.');
    }

    public function acceptInvitation(ProgramCommittee $invitation)
    {
        if ($invitation->status !== ProgramCommittee::STATUS_PENDING) {
            return view('pc-invitations.response', [
                'status' => 'error',
                'message' => 'This invitation has already been responded to.'
            ]);
        }

        // Update invitation status
        $invitation->update([
            'status' => ProgramCommittee::STATUS_ACCEPTED,
            'responded_at' => now()
        ]);

        // Get all submitted papers from this conference
        $papers = Paper::where('conference_id', $invitation->conference_id)
            ->where('status', 'submitted')
            ->get();

        // Create review records for each paper
        foreach ($papers as $paper) {
            // Only create if review doesn't already exist
            if (!$paper->reviews()->where('reviewer_id', $invitation->user_id)->exists()) {
                Review::create([
                    'paper_id' => $paper->id,
                    'reviewer_id' => $invitation->user_id,
                    'status' => Review::STATUS_PENDING,
                    'recommendation' => Review::RECOMMEND_PENDING
                ]);
            }
        }

        return view('pc-invitations.response', [
            'status' => 'success',
            'message' => 'You have successfully accepted the invitation to join the Program Committee. You have been assigned to review all submitted papers.'
        ]);
    }

    public function rejectInvitation(ProgramCommittee $invitation)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login', ['redirect' => url()->current()]);
        }

        // Check if invitation is still pending
        if ($invitation->status !== ProgramCommittee::STATUS_PENDING) {
            return view('pc-invitations.response', [
                'status' => 'error',
                'message' => 'This invitation has already been responded to.'
            ]);
        }

        // Update invitation status
        $invitation->update([
            'status' => ProgramCommittee::STATUS_REJECTED,
            'responded_at' => now()
        ]);

        return view('pc-invitations.response', [
            'status' => 'success',
            'message' => 'You have declined the invitation to join the Program Committee.'
        ]);
    }
}
