@component('mail::message')
# Program Committee Invitation

Dear {{ $pc->user->name }},

You have been invited to join the Program Committee for the conference: **{{ $pc->conference->title }}**

@if($pc->invitation_message)
**Message from the Conference Chair:**
{{ $pc->invitation_message }}
@endif

@component('mail::button', ['url' => route('pc-invitations.accept', $pc)])
Accept Invitation
@endcomponent

@component('mail::button', ['url' => route('pc-invitations.reject', $pc), 'color' => 'red'])
Decline Invitation
@endcomponent

If you have any questions, please contact the conference chair.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
